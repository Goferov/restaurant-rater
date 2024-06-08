<?php

namespace App\Controllers;
use App\Models\User;
use App\Repository\IUserRepository;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;
use App\Utils\Validators\IValidator;

class UserController extends AppController {

    private IUserRepository $userRepository;
    private Request $request;
    private Session $session;
    private IValidator $passwordValidator;
    private Redirect $redirect;

    public function __construct(
        IUserRepository $userRepository,
        Request         $request,
        Session         $session,
        IValidator      $passwordValidator,
        Redirect        $redirect,
    ) {
        $this->userRepository = $userRepository;
        $this->request = $request;
        $this->session = $session;
        $this->passwordValidator = $passwordValidator;
        $this->redirect = $redirect;
    }

    public function login() {

        if(!$this->request->isPost()) {
            $this->redirect->to('/');
        }

        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $redirect = $this->redirect->getPreviousPage();
        $user = $this->userRepository->getUser($email);

        if($user) {
            if (!password_verify($password, $user->getPassword())) {
                $this->redirect->to($redirect, ['loginMessage' => 'wrongPassword']);
            }
        }
        else {
            $this->redirect->to($redirect, ['loginMessage' => 'userNotExist']);
        }

        $this->session->set('userSession', [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roleId' => $user->getRoleId(),
            ]
        );
        $this->redirect->to('/panel');
    }

    public function register(): void {
        if(!$this->request->isPost()) {
            $this->redirect->to('/');
        }

        $email = filter_var($this->request->post('email'), FILTER_SANITIZE_EMAIL);
        $name = $this->request->post('name');
        $password = $this->request->post('password');
        $confirmedPassword = $this->request->post('confirmedPassword');
        $redirect = $this->redirect->getPreviousPage();
        $role = 2; // TODO: IMPLEMENT ROLE MANAGEMENT

        if(!$this->passwordValidator->validate($password)) {
            $this->redirect->to($redirect, ['registerMessage' => 'invalidPassword']);
        }

        if ($password !== $confirmedPassword)  {
            $this->redirect->to($redirect, ['registerMessage' => 'passwordsNotMatch']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect->to($redirect, ['registerMessage' => 'wrongEmail']);
        }

        $userFromDb = $this->userRepository->getUser($email);
        if($userFromDb) {
            $this->redirect->to($redirect, ['registerMessage' => 'userExist']);
        }


        $user = new User(null, $name, password_hash($password, PASSWORD_BCRYPT), $email, $role);
        $this->userRepository->addUser($user);
        $this->redirect->to($redirect, ['registerMessage' => 'registerComplete']);
    }

    public function changePassword() {
        $loggedUser = $this->session->get('userSession');

        if(!$this->request->isPost() || !$loggedUser) {
            $this->redirect->to('/');
        }

        $currentPassword = $this->request->post('currentPassword');
        $newPassword = $this->request->post('newPassword');
        $repeatNewPassword = $this->request->post('repeatNewPassword');
        $user = $this->userRepository->getUser($loggedUser['email']);
        $redirect = '/panel';

        if($user) {
            if (!password_verify($currentPassword, $user->getPassword())) {
                $this->redirect->to($redirect, ['message' => 'wrongPassword']);
            }

            if(!$this->passwordValidator->validate($newPassword)) {
                $this->redirect->to($redirect, ['message' => 'invalidPassword']);
            }

            if ($newPassword !== $repeatNewPassword)  {
                $this->redirect->to($redirect, ['message' => 'passwordsNotMatch']);
            }
        }
        else {
            $this->logout();
        }

        $this->userRepository->updateUserPassword($user->getId(), password_hash($newPassword, PASSWORD_BCRYPT));
        $this->redirect->to($redirect, ['message' => 'passwordChange']);
    }

    public function logout(): void {
        $this->session->remove('userSession');
        $this->redirect->to('/');
    }
}