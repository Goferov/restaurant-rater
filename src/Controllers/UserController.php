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

        $this->session->set('userSession', [
            'id' => $user->getName(),
            'email' => $user->getEmail(),
            'name' => $user->getName()
            ]
        );
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

        if(!$this->isValidPassword($password)) {
            $this->redirect($redirect, ['message' => 'invalidPassword']);
        }

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

    public function changePassword() {
        $loggedUser = $this->session->get('userSession');

        if(!$this->request->isPost() || !$loggedUser) {
            $this->redirect('/');
        }

        $currentPassword = $this->request->post('currentPassword');
        $newPassword = $this->request->post('newPassword');
        $repeatNewPassword = $this->request->post('repeatNewPassword');
        $user = $this->userRepository->getUser($loggedUser['email']);
        $redirect = '/panel';

        if($user) {
            if (!password_verify($currentPassword, $user->getPassword())) {
                $this->redirect($redirect, ['message' => 'wrongPassword']);
            }

            if(!$this->isValidPassword($newPassword)) {
                $this->redirect($redirect, ['message' => 'invalidPassword']);
            }

            if ($newPassword !== $repeatNewPassword)  {
                $this->redirect($redirect, ['message' => 'passwordsNotMatch']);
            }
        }
        else {
            $this->logout();
        }

        $this->userRepository->updateUserPassword($user->getId(), password_hash($newPassword, PASSWORD_BCRYPT));
        $this->redirect($redirect, ['message' => 'passwordChange']);
    }

    public function logout(): void {
        $this->session->remove('userSession');
        $this->redirect('/');
    }

    private function isValidPassword(string $password): bool {
        $minLength = 6;
        return strlen($password) >= $minLength && preg_match('/\d/', $password);
    }
}