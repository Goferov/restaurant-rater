<?php

namespace App\Controllers;

class UserController extends AppController {
    public function login() {
        $login = $this->request->post('login');
        $password = $this->request->post('password');
    }

    public function register() {

    }

    public function logout() {
        $this->session->remove('user_session');
        $this->redirect('/');
    }
}