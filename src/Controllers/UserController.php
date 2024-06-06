<?php

namespace App\Controllers;
use App\Repository\UserRepository;
use App\Models\User;
use App\Repository\UserRepositoryI;
use App\Request;
use App\Session;
use App\Validators\IValidator;

class UserController extends AppController {

    private UserRepositoryI $userRepository;
    private Request $request;
    private Session $session;
    private IValidator $passwordValidator;

    public function __construct(
        UserRepositoryI $userRepository,
        Request         $request,
        Session         $session,
        IValidator      $passwordValidator,
    ) {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->request = $request;
        $this->session = $session;
        $this->passwordValidator = $passwordValidator;
    }

    public function login() {

        if(!$this->request->isPost()) {
            $this->redirect('/');
        }

        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $redirect = $this->getPreviousPage();
        $user = $this->userRepository->getUser($email);

        if($user) {
            if (!password_verify($password, $user->getPassword())) {
                $this->redirect($redirect, ['loginMessage' => 'wrongPassword']);
            }
        }
        else {
            $this->redirect($redirect, ['loginMessage' => 'userNotExist']);
        }

        $this->session->set('userSession', [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roleId' => $user->getRoleId(),
            ]
        );
        $this->redirect('/panel');
    }

    public function register(): void {
        if(!$this->request->isPost()) {
            $this->redirect('/');
        }

        $email = filter_var($this->request->post('email'), FILTER_SANITIZE_EMAIL);
        $name = $this->request->post('name');
        $password = $this->request->post('password');
        $confirmedPassword = $this->request->post('confirmedPassword');
        $redirect = $this->getPreviousPage();
        $role = 2; // TODO: IMPLEMENT ROLE MANAGEMENT

        if(!$this->passwordValidator->validate($password)) {
            $this->redirect($redirect, ['registerMessage' => 'invalidPassword']);
        }

        if ($password !== $confirmedPassword)  {
            $this->redirect($redirect, ['registerMessage' => 'passwordsNotMatch']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect($redirect, ['registerMessage' => 'wrongEmail']);
        }

        $userFromDb = $this->userRepository->getUser($email);
        if($userFromDb) {
            $this->redirect($redirect, ['registerMessage' => 'userExist']);
        }


        $user = new User(null, $name, password_hash($password, PASSWORD_BCRYPT), $email, $role);
        $this->userRepository->addUser($user);
        $this->redirect($redirect, ['registerMessage' => 'registerComplete']);
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

            if(!$this->passwordValidator->validate($newPassword)) {
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
}