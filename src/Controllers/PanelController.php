<?php
namespace App\Controllers;

use App\Config;

class PanelController extends AppController {
    public function panel() {
        if($this->session->get('userSession')) {
            $messagesList = Config::get('messages');
            $messageKey = $this->request->get('message');

            $this->render('panel', ['message' => $messagesList[$messageKey] ?? null]);
        }
        else {
            $this->redirect('/');
        }
    }
}