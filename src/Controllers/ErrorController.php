<?php

namespace App\Controllers;

class ErrorController extends AppController
{
    public function error404()
    {
        $this->render('404');
    }
}