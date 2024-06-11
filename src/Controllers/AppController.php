<?php

namespace App\Controllers;

use App\Config;

class AppController {

    private static string $main_template_path = 'public/views/template.php';

    public function render(string $page = null, array $variables = []): void
    {
        $templatePath = 'public/views/pages/'. $page.'.php';
        $output = 'FileService not found';

        if(file_exists($templatePath)) {
            $variables = array_merge($variables, $this->getGlobalVariables());
            extract($variables);
            ob_start();
            include self::$main_template_path;
            $output = ob_get_clean();
        }
        print $output;
    }

    private function getGlobalVariables(): array {
        $messageList = Config::get('messages');
        $loginMessageKey = $_GET['loginMessage'] ?? null;
        $registerMessageKey = $_GET['registerMessage'] ?? null;

        return [
            'isLogin' => $_SESSION['userSession'] ?? null,
            'loginMessage' => $messageList[$loginMessageKey] ?? null,
            'registerMessage' => $messageList[$registerMessageKey] ?? null,
        ];
    }

}