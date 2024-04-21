<?php

namespace App\Controllers;

use App\Config;
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
        $variables = array_merge($variables, $this->getGlobalVariables());

        if(file_exists($templatePath)) {
            extract($variables);
            ob_start();
            include self::$main_template_path;
            $output = ob_get_clean();
        }
        print $output;
    }

    protected function redirect(string $to, array $params = []):void {
        $location = 'http://' . $this->request->server('HTTP_HOST') . $to . $this->buildUrlParams($params);
        header('Location: '.$location);
        exit();
    }

    private function buildUrlParams($params) {
        if($params) {
            $queryParams = [];
            foreach ($params as $key=>$param) {
                $queryParams[] = urlencode($key) . '=' . urlencode($param);
            }
            $queryParams = implode('&',$queryParams);
            return '?' . $queryParams;
        }
    }

    private function getGlobalVariables(): array {
        $messageList = Config::get('messages');
        $loginMessageKey = $this->request->get('loginMessage');
        $registerMessageKey = $this->request->get('loginMessage');

        return [
            'isLogin' => $this->session->get('userSession'),
            'loginMessage' => $messageList[$loginMessageKey] ?? null,
            'registerMessage' =>$messageList[$registerMessageKey] ?? null,
        ];
    }

}