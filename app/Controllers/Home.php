<?php
namespace App\Controllers;
use App\Models\LogModel;

class Home extends BaseController
{
    private $logModel;
    private $encrypter;
    public function __construct()
    {
        $this->logModel = new LogModel();
        $this->encrypter = \Config\Services::encrypter();
        date_default_timezone_set('Etc/GMT-2');
    }
    public function index(): string
    {
        return view('home');
    }

    public function message()
    {
        return view('message');
    }

    public function fileMessage()
    {
        return view('file_message');
    }

    public function receiveMessage()
    {
        try {
            // Get the data parameter from the URL
            $dataParam = $this->request->getGet('data');

            // Decode the URL-encoded data parameter
            $decodedData = urldecode($dataParam);

            // Decrypt the ID
            $decryptedId = $this->encrypter->decrypt(hex2bin($decodedData));

            // Find the log with the decrypted ID
            $log = $this->logModel->where('id', $decryptedId)->find();

            if ($log) {
                // Decrypt the message
                $hold = $this->encrypter->decrypt(hex2bin($log[0]['Message']));
                $log[0]['Message'] = $hold;
                return view('receive_message', ['data' => $log]);
            } else {
                // Log with the decrypted ID not found
                return view('receive_message', ['data' => null]);
            }
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return view('receive_message', ['data' => null]);
        }
    }

    public function downloadFile()
    {
        // Get the data parameter from the URL
        $dataParam = $this->request->getGet('path');
        $file = FCPATH . 'files/'. $this->encrypter->decrypt(hex2bin($dataParam));

        // Check if the file exists
        if (file_exists($file)) {
            return $this->response->download($file, null);
        } else {
            // Handle the case where the file doesn't exist (e.g., show an error message)
            return 'File not found';
        }
    }

    public function processTime($value){
        // Get the current time
        $currentTime = new \DateTime();

        // Use a switch statement to handle different cases
        switch ($value) {
            case 1:
                // Add 2 minutes
                $currentTime->add(new \DateInterval('PT2M'));
                break;
            case 2:
                // Add 15 minutes
                $currentTime->add(new \DateInterval('PT15M'));
                break;
            case 3:
                // Add 1 hour
                $currentTime->add(new \DateInterval('PT1H'));
                break;
            case 4:
                // Add 1 day
                $currentTime->add(new \DateInterval('P1D'));
                break;
            default:
                // Invalid value, do nothing or handle accordingly
                break;
    }

    // Return the result as a formatted string (e.g., 'Y-m-d H:i:s')
    return $currentTime->format('Y-m-d H:i:s');
    }

    public function submitMessage(){
        if($this->request->getMethod() == 'post')
        { 
            $option = $this->request->getPost('expire');
            $expiration = $this->processTime($option);
            $dateTime = new \DateTime();
            
            $messageData = [
                'Message' => bin2hex($this->encrypter->encrypt($this->request->getPost('message'))),
                'Expire' => $expiration,
                'CreatedAt' => $dateTime->format('Y-m-d H:i:s'),
            ];

            if ($this->logModel->save($messageData))
            {
                $insertedPrimaryKeyValue = $this->logModel->getInsertID();
                $link = $this->makeURL($insertedPrimaryKeyValue);
                // break string into multiple if many emails are given
                $emailz = explode(";", $this->request->getPost('email'));
                // filter empty strings
                $filteredEmailz = array_filter($emailz);
                $result = $this->sendEmail($filteredEmailz, $link);
                return $this->response->setJSON(['link' => $link]);
            }
            else
            {
                return $this->response->setJSON(['error' => $this->logModel->errors()]);
            }
        }
    }

    public function makeURL($id) :string
    {
        $data= bin2hex($this->encrypter->encrypt($id));
        return 'receiveMessage?data=' . urlencode($data);
    }

    public function upload_files(){
        $uploadedFiles = $this->request->getFiles();
        $uploadedFileNames = [];

        foreach ($uploadedFiles['customFiles'] as $file) {
            if ($file !== null && $file->isValid() && !$file->hasMoved()) {
                $name = $file->getRandomName();
        
                // Move the uploaded file to the desired directory
                $file->move(getenv('baseURL') . ('files/'), $name);
    
                $uploadedFileNames[] = $name;
            } 
            else 
            {
                $errors = $file->getErrorString();
                return 'Error: ' . $errors;
            }
        }
        // make a zip file
        $zip = new \ZipArchive();
        $zipFileName = 'generated_' . uniqid() . '.zip';
        if ($zip->open('files/'.$zipFileName, \ZipArchive::CREATE) === TRUE) {
            foreach ($uploadedFileNames as $file) {
                    $zip->addFile(getenv('baseURL') . ('files/'). $file, basename($file));
            }
            $zip->close();
        }

        // cleanup
        foreach($uploadedFileNames as $file)
        {
            unlink(getenv('baseURL') . ('files/'). $file);
        }
        
        return $zipFileName;
    }

    public function submitFiles(){
        if($this->request->getMethod() == 'post')
        { 
            $encryption = \Config\Services::encryption();

            $option = $this->request->getPost('expire');
            $expiration = $this->processTime($option);
            $dateTime = new \DateTime();
            $filename = $this->upload_files();
            $messageData = [
                'Message' => bin2hex($this->encrypter->encrypt($this->request->getPost('message'))),
                'Expire' => $expiration,
                'CreatedAt' => $dateTime->format('Y-m-d H:i:s'),
                'File' => bin2hex($this->encrypter->encrypt($filename)),
            ];
            if ($this->logModel->save($messageData))
            {
                $insertedPrimaryKeyValue = $this->logModel->getInsertID();
                $link = $this->makeURL($insertedPrimaryKeyValue);
                // break string into multiple if many emails are given
                $emailz = explode(";", $this->request->getPost('email'));
                // filter empty strings
                $filteredEmailz = array_filter($emailz);
                $result = $this->sendEmail($filteredEmailz, $link);
                return $this->response->setJSON(['link' => $link]);
            }
            else
            {
                return $this->response->setJSON(['error' => $this->logModel->errors()]);
            }
        }
    }

    public function sendEmail($emails, $link)
    {
        foreach($emails as $emailOne)
        {
            $email = \Config\Services::email();
            $email->setFrom('no-reply-messaging-bot@outlook.com');
            $email->setSubject('You\'ve been sent a message');
            $email->setMessage(base_url(). 'public/'. $link);

            // Set the recipient email address
            $email->setTo($emailOne);
            
            // Send the email
            $result = $email->send();
            
            // Check if sending failed
            if(!$result){
                return $result;
            }
        }
        return;
    }

    public function cleanup(){
        if($this->request->getMethod() == 'delete'){
            // TODO delete expired log
        }
    }
}
