<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function isLoggedIn()
    {
        return session()->has('user');
    }
    public function uploadFile($file, $folder)
    {
        $errors = array();
        $file_name = time() . "_" . trim($file['name']);
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];

        if ($file_size > 209715200) {
            $errors[] = 'File size must be less than 200 MB';
        }
        move_uploaded_file($file_tmp, $folder . "/" . $file_name);
        $response = array();
        $response['errors'] = $errors;
        $response['success'] = false;
        $response['file_name'] = $file_name;
        return $response;
    }
    public function deleteFile($filename, $path)
    {
        $response = array();
        $response['errors'] = false;
        $response['success'] = false;
        $response['file_name'] = $filename;
        if (file_exists("uploads/" . $path . "/" . $filename) && unlink("uploads/" . $path . "/" . $filename)) {
            $response['errors'] = true;
            $response['success'] = true;
            return $response;
        } else {
            echo "file doesn't exist";
        }
        return $response;
    }
}
