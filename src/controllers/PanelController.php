<?php
namespace App\Controllers;

class PanelController extends AppController {
    public function panel() {
        if($this->session->get('user_session')) {
            $this->render('panel');
        }
        else {
            $this->redirect('/');
        }
    }
}