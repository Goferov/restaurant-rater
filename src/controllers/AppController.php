<?php

namespace App\Controllers;

use App\Request;
use App\Session;

class AppController {

    protected Request $request;
    protected Session $session;
    private static string $main_template_path = 'public/views/template.php';

    public function __construct()
    {
        $this->request = new Request($_GET, $_POST, $_SERVER);
        $this->session = new Session();
    }
    public function render(string $page = null, array $variables = []): void
    {
        $templatePath = 'public/views/pages/'. $page.'.php';
        $output = 'File not found';
        $is_login = $this->session->get('user_session');

        if(file_exists($templatePath)) {
            extract($variables);
            ob_start();
            include self::$main_template_path;
            $output = ob_get_clean();
        }
        print $output;
    }

    protected function redirect(string $to):void {
        $location = $to;
        header('Location: '.$location);
        exit();
    }
}