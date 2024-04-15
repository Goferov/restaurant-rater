<?php

namespace App\Controllers;

use App\Request;

class AppController {

    protected Request $request;
    private static string $main_template_path = 'public/views/template.php';

    function __construct()
    {
        $this->request = new Request($_GET, $_POST, $_SERVER);
    }
    public function render(string $page = null, array $variables = []): void
    {
        $templatePath = 'public/views/pages/'. $page.'.php';
        $output = 'File not found';

        if(file_exists($templatePath)) {
            extract($variables);
            ob_start();
            include self::$main_template_path;
            $output = ob_get_clean();
        }
        print $output;
    }
}