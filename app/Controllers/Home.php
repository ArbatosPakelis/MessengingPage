<?php
namespace App\Controllers;
use App\Models\LogModel;
use App\Models\EmailQueueModel;
use App\Models\UserModel;

class Home extends BaseController
{
    private $logModel;
    private $emailQueueModel;

    private $encrypter;
    public function __construct()
    {
        $this->session= \Config\Services::session();
        $this->session->start();
        $this->logModel = new LogModel();
        $this->emailQueueModel = new EmailQueueModel();
        $this->userModel = new UserModel();
        $this->encrypter = \Config\Services::encrypter();
        date_default_timezone_set('Etc/GMT-2');
    }
    public function index(): string
    {
        $sessionData = [
            'id' => session()->get('id'),
            'name' => session()->get('name'),
            'isLoggedIn' => session()->get('isLoggedIn')
        ];
        return view('home', $sessionData);
    }

    public function message()
    {
        $sessionData = [
            'id' => session()->get('id'),
            'name' => session()->get('name'),
            'isLoggedIn' => session()->get('isLoggedIn')
        ];
        return view('message', $sessionData);
    }

    public function fileMessage()
    {
        $sessionData = [
            'id' => session()->get('id'),
            'name' => session()->get('name'),
            'isLoggedIn' => session()->get('isLoggedIn')
        ];
        return view('file_message', $sessionData);
    }

    public function profile()
    {
        $sessionData = [
            'id' => session()->get('id'),
            'name' => session()->get('name'),
            'isLoggedIn' => session()->get('isLoggedIn')
        ];
        $logs = $this->logModel->where('Users_FK', session()->get('id'))->find();
        foreach ($logs as &$log) {
            $encriptedId = urlencode(bin2hex($this->encrypter->encrypt($log['Id']))); // Assuming 'id' is the primary key of the log table
            $log['encriptedId'] = $encriptedId;
        }
        return view('profile', ['session' =>$sessionData, 'data' =>$logs]);
    }

    public function receiveMessage()
    {
        $sessionData = [
            'id' => session()->get('id'),
            'name' => session()->get('name'),
            'isLoggedIn' => session()->get('isLoggedIn')
        ];

        try {
            // Get the data parameter from the URL
            $dataParam = $this->request->getGet('data');

            // Decode the URL-encoded data parameter
            $decodedData = urldecode($dataParam);

            // Decrypt the ID
            $decryptedId = $this->encrypter->decrypt(hex2bin($decodedData));

            // Find the log with the decrypted ID
            $log = $this->logModel->where('Id', $decryptedId)->find();

            // delete log if it's expired
            $check = $this->cleanup($log);

            // get log again in case it was deleted
            $logs = $this->logModel->where('Id', $decryptedId)->find();

            if ($logs) {
                // Decrypt the message
                $hold = $this->encrypter->decrypt(hex2bin($logs[0]['Message']));
                $logs[0]['Message'] = $hold;
                return view('receive_message', ['data' => $logs, 'session' => $sessionData]);
            } else {
                // Log with the decrypted ID not found
                return view('receive_message', ['data' => "404", 'session' => $sessionData]);
            }
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return view('receive_message', ['data' => "500", 'session' => $sessionData]);
        }
    }

    // public function downloadFile()
    // {
    //     // Get the data parameter from the URL
    //     $dataParam = $this->request->getGet('path');
    //     $file = FCPATH . 'files/'. $this->encrypter->decrypt(hex2bin($dataParam));

    //     // Check if the file exists
    //     if (file_exists($file)) {
    //         return $this->response->download($file, null);
    //     } else {
    //         // Handle the case where the file doesn't exist (e.g., show an error message)
    //         return 'File not found';
    //     }
    // }

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
            
            if(session()->get('id') < 1)
            {
                $messageData = [
                    'Message' => bin2hex($this->encrypter->encrypt($this->request->getPost('message'))),
                    'Expire' => $expiration,
                    'CreatedAt' => $dateTime->format('Y-m-d H:i:s'),
                ];
            }
            else
            {
                $messageData = [
                    'Message' => bin2hex($this->encrypter->encrypt($this->request->getPost('message'))),
                    'Expire' => $expiration,
                    'CreatedAt' => $dateTime->format('Y-m-d H:i:s'),
                    'Users_FK' => session()->get('id'),
                ];
            }

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
        $pass = $this->request->getPost('password');

        foreach ($uploadedFiles['customFiles'] as $file) {
            if ($file !== null && $file->isValid() && !$file->hasMoved()) {
                $name = $file->getRandomName();
        
                // Move the uploaded file to the desired directory
                $file->move(WRITEPATH . ('files/'), $name);
    
                $uploadedFileNames[] = $name;
            }
        }
        // make a zip file
        $zip = new \ZipArchive();
        $zipFileName = 'generated_' . uniqid() . '.zip';
        //return  trim($uploadedFiles['customFiles'][0]) !== '';
        if(trim($uploadedFiles['customFiles'][0]) !== '')
        {
            $holding = $zip->open(WRITEPATH . 'files/'.$zipFileName, \ZipArchive::CREATE);
            if($this->request->getPost('password') !== null && $this->request->getPost('password') !== ""){
                $zip->setPassword($pass);
            }
            if ($holding === true) {
                foreach ($uploadedFileNames as $file) {
                        $zip->addFile(WRITEPATH . ('files/'). $file, basename($file));
                        if($this->request->getPost('password') !== null && $this->request->getPost('password') !== ""){
                            $zip->setEncryptionName(basename($file), \ZipArchive::EM_AES_256);
                        }
                }
            }
            $zip->close();
            // cleanup
            foreach($uploadedFileNames as $file)
            {
                unlink(WRITEPATH . ('files/'). $file);
            }
            
            return bin2hex($this->encrypter->encrypt($zipFileName));
        }
        else
        {
            return null;
        }
    }

    public function submitFiles(){
        if($this->request->getMethod() == 'post')
        { 
            $encryption = \Config\Services::encryption();

            $option = $this->request->getPost('expire');
            $expiration = $this->processTime($option);
            $dateTime = new \DateTime();
            $filename = $this->upload_files();
            //return $this->response->setJSON(['status' => $filename]);
            if($this->request->getPost('password') !== null && $this->request->getPost('password') !== "")
            {
                $pass = bin2hex($this->encrypter->encrypt($this->request->getPost('password')));
            }
            else
            {
                $pass = null;
            }

            if(session()->get('id') < 1)
            {
                $messageData = [
                    'Message' => bin2hex($this->encrypter->encrypt($this->request->getPost('message'))),
                    'Expire' => $expiration,
                    'CreatedAt' => $dateTime->format('Y-m-d H:i:s'),
                    'File' => $filename,
                    'Password'  => $pass,
                ];
            }
            else
            {
                $messageData = [
                    'Message' => bin2hex($this->encrypter->encrypt($this->request->getPost('message'))),
                    'Expire' => $expiration,
                    'CreatedAt' => $dateTime->format('Y-m-d H:i:s'),
                    'File' => $filename,
                    'Password'  => $pass,
                    'Users_FK' => session()->get('id'),
                ];
            }

            if ($this->logModel->save($messageData))
            {
                $insertedPrimaryKeyValue = $this->logModel->getInsertID();
                $link = $this->makeURL($insertedPrimaryKeyValue);
                // break string into multiple if many emails are given
                $emailz = explode(";", $this->request->getPost('email'));
                // filter empty strings
                $filteredEmailz = array_filter($emailz);
                $result = $this->sendFileEmail($filteredEmailz, $link, $pass);
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
        if(isset($emails)){
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
        }
        return;
    }

    public function sendFileEmail($emails, $link, $pass)
    {
        if(isset($emails)){
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

                $emailQueueData = [
                    'Email' => $emailOne,
                    'Password'  => $pass,
                ];

                $result = $this->emailQueueModel->save($emailQueueData);
                if (!$result)
                {
                    return $this->response->setJSON(['error' => $this->logModel->errors()]);
                }
            }
        }
        return;
    }

    public function cleanup($log){
        $currentTime = time();
        $expiration = strtotime($log[0]['Expire']);
        if($expiration < $currentTime){

            $logg = $this->logModel->find($log[0]['Id']);
            $holding = intval($logg['Views'])+1;
            $this->logModel->where('Id', $log[0]['Id'])->set('Views', $holding)->update();
            if($log[0]['File'] != null && $log[0]['File'] != ''){
                unlink(getenv('baseURL') . ('files/'). $this->encrypter->decrypt(hex2bin($log[0]['File'])));
                return true;
            }
            return true;
        }
        return false;
    }

    public function processEmails(){
        $emails = $this->emailQueueModel->findAll();
        
        foreach($emails as $emailOne){
            $email = \Config\Services::email();
            $email->setFrom('no-reply-messaging-bot@outlook.com');
            $email->setSubject('You\'ve been sent a message');
            try {
                $password = $emailOne['Password'];
                if ($password !== null) {
                    $decryptedPassword = $this->encrypter->decrypt(hex2bin($password));
                    $email->setMessage('Password: ' . $decryptedPassword);
                    $this->emailQueueModel->delete($emailOne['Id']);
                } else {
                    $email->setMessage('No password provided');
                }
            } catch (\CodeIgniter\Security\Exceptions\SecurityException $e) {
                // Handle decryption failure
                $email->setMessage('Failed to retrieve the password: ' . $e->getMessage());
            } catch (\RuntimeException $e) {
                // Handle other runtime exceptions
                $email->setMessage('Runtime error during decryption: ' . $e->getMessage());
            }

            // Set the recipient email address
            $email->setTo($emailOne['Email']);
            
            // Send the email
            $result = $email->send();
            if(!$result)
            {
                return $result;
            }
        }
        return;
    }

    public function login()
    {
        if($this->request->getMethod() == 'get')
        {
            return view('login');
        }
        else if($this->request->getMethod() == 'post')
        {
            $userData = [
                'Username' => $this->request->getPost('username'),
                'Password' => $this->request->getPost('password'),
            ];
            $login = $this->authenticate($userData);
            return $this->response->setJSON(['status' =>'sucess']);
        }
    }

    public function signup()
    {
        if($this->request->getMethod() == 'get')
        {
            return view('signup');
        }
        else if($this->request->getMethod() == 'post')
        {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $email = $this->request->getPost('email');

            $existingUser = $this->userModel->where('Username', $username)->first();
            if ($existingUser) {
                return $this->response->setStatusCode(403)->setJSON(['error' => ' username is already taken']);
            }
            
            $userData = [
                'Username' => $username,
                'Password' => bin2hex($this->encrypter->encrypt($password)),
                'Email' => $email,
            ];

            if ($this->userModel->save($userData))
            {
                return $this->response->setJSON(['success' => 'User registered successfully']);
            }
            else
            {
                return $this->response->setStatusCode(500)->setJSON(['error' => $this->userModel->errors()]);
            }
        }
    }

    function authorize()
    {
        if (!session()->get('isLoggedIn') || session()->get('id') < 1 || session()->get('name') == "")
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    function authenticate($data)
    {
        if(isset($data))
        {
            $username = $data['Username'];
            $password = $data['Password'];

            $existingUser = $this->userModel->where('Username', $username)->first();
            if (!$existingUser) {
                return false;
            }

            $savedPassword = $this->encrypter->decrypt(hex2bin($existingUser['Password']));
            
            if($savedPassword == $password)
            {
                $ses_data = [
                    'id' => $existingUser['Id'],
                    'name' => $existingUser['Username'],
                    'isLoggedIn' => True
                ];   
                $this->session->set($ses_data);
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    function logout()
    {
        $ses_data = [
            'id' => -1,
            'name' => '',
            'isLoggedIn' => false
        ];
        $this->session->set($ses_data);
    }
}
