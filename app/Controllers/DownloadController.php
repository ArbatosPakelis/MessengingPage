<?php
namespace App\Controllers;

class DownloadController extends BaseController
{
    public function download($filename)
    {
        $encrypter = \Config\Services::encrypter();
        $file = WRITEPATH . 'files/'. $encrypter->decrypt(hex2bin($filename));

        // Check if the file exists
        if (file_exists($file)) {
            return $this->response->download($file, null);
        } else {
            // Handle the case where the file doesn't exist (e.g., show an error message)
            return 'File not found';
        }
    }
}

?>