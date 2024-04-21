<?php

namespace App\Controllers;
use App\Repository\UserRepository;
use App\Models\User;

class UserController extends AppController {

    private UserRepository $userRepository;
    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login() {

        if(!$this->request->isPost()) {
            $this->redirect('/');
        }

        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $redirect = $this->request->post('redirect') ?? '/';

        $user = $this->userRepository->getUser($email);

        if($user) {
            if (!password_verify($password, $user->getPassword())) {
                $this->redirect($redirect, ['message' => 'wrongPassword']);
            }
        }
        else {
            $this->redirect($redirect, ['message' => 'userNotExist']);
        }

        $this->session->set('userSession', $user);
        $this->redirect($redirect);
    }

    public function register(): void {
        if(!$this->request->isPost()) {
            $this->redirect('/');
        }

        $email = $this->request->post('email');
        $name = $this->request->post('name');
        $password = $this->request->post('password');
        $confirmedPassword = $this->request->post('confirmedPassword');
        $redirect = $this->request->post('redirect') ?? '';
        $role = 2; // TODO: IMPLEMENT ROLE MANAGEMENT

        if ($password !== $confirmedPassword)  {
            $this->redirect($redirect, ['message' => 'passwordsNotMatch']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect($redirect, ['message' => 'wrongEmail']);
        }

        $user = new User(null, $name, password_hash($password, PASSWORD_BCRYPT), $email, $role);
        $this->userRepository->addUser($user);
        $this->redirect($redirect, ['message' => 'registerComplete']);
    }

    public function logout(): void {
        $this->session->remove('userSession');
        $this->redirect('/');
    }
}