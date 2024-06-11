<?php


use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;
use App\Models\User;
use App\Repository\IUserRepository;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;
use App\Utils\Validators\IValidator;

class UserControllerTest extends TestCase {
    private IUserRepository $userRepositoryMock;
    private Request $requestMock;
    private Session $sessionMock;
    private IValidator $passwordValidatorMock;
    private Redirect $redirectMock;
    private UserController $userController;

    protected function setUp(): void {
        $this->userRepositoryMock = $this->createMock(IUserRepository::class);
        $this->requestMock = $this->createMock(Request::class);
        $this->sessionMock = $this->createMock(Session::class);
        $this->passwordValidatorMock = $this->createMock(IValidator::class);
        $this->redirectMock = $this->createMock(Redirect::class);
        $this->redirectMock->method('getPreviousPage')->willReturn('/fallback-url');

        $this->userController = new UserController(
            $this->userRepositoryMock,
            $this->requestMock,
            $this->sessionMock,
            $this->passwordValidatorMock,
            $this->redirectMock
        );
    }

    public function testLoginPostNotSetRedirectsToHome() {
        $this->requestMock->method('isPost')->willReturn(false);
        $this->redirectMock->expects($this->once())->method('to')->with('/');

        $this->userRepositoryMock->expects($this->never())->method('getUser');

        $this->userController->login();
    }

    public function testLoginWithInvalidCredentials() {
        $this->requestMock->method('isPost')->willReturn(true);
        $this->requestMock->method('post')->willReturnMap([
            ['email', null, 'user@example.com'],
            ['password', null, 'wrongpassword']
        ]);
        $this->userRepositoryMock->method('getUser')->willReturn(null);
        $this->redirectMock->expects($this->once())->method('to');

        $this->userController->login();
    }

    public function testLoginValidCredentials() {
        $user = new User(1, 'John', password_hash('correctpassword', PASSWORD_DEFAULT), 'user@example.com', 1);

        $this->requestMock->method('isPost')->willReturn(true);
        $this->requestMock->method('post')->willReturnMap([
            ['email', null, 'user@example.com'],  // ensure email is correctly set
            ['password', null, 'correctpassword']
        ]);
        $this->userRepositoryMock->method('getUser')->willReturn($user);
        $this->sessionMock->expects($this->once())->method('set')->with(
            'userSession', [
                'id' => 1,
                'email' => 'user@example.com',
                'name' => 'John',
                'roleId' => 1
            ]
        );
        $this->redirectMock->expects($this->once())->method('to')->with('/panel');

        $this->userController->login();
    }

}
