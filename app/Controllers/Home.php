<?php

namespace App\Controllers;

class Home extends BaseController
{
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
        return view('receive_message');
    }
}
