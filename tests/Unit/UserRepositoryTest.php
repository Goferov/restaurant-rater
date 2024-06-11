<?php

use App\Database;
use App\Models\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private $userRepository;
    private $pdoMock;
    private $stmtMock;
    private $databaseMock;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->databaseMock = $this->createMock(Database::class);
        $this->databaseMock->method('connect')->willReturn($this->pdoMock);
        $this->userRepository = new UserRepository($this->databaseMock);
    }

    public function testGetUser() {
        $email = "test@example.com";
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())->method('bindParam')->with(':email', $email, PDO::PARAM_STR);
        $this->stmtMock->expects($this->once())->method('execute');
        $this->stmtMock->method('fetch')->willReturn([
            'user_id' => 1,
            'name' => 'John Doe',
            'password' => 'hashedpassword',
            'email' => $email,
            'role_id' => 2
        ]);

        $user = $this->userRepository->getUser($email);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testAddUser() {
        $user = new User(null, 'John Doe', 'hashedpassword', 'test@example.com', 2);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())->method('execute')->with([
            $user->getName(),
            $user->getPassword(),
            $user->getEmail(),
            $user->getRoleId()
        ]);

        $this->userRepository->addUser($user);
    }
    public function testUpdateUserPassword() {
        $id = 1;
        $newPassword = 'newhashedpassword';
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(2))->method('bindParam')->willReturnCallback(
            function ($param, &$value, $type) use ($id, $newPassword) {
                static $count = 0;
                $count++;
                if ($count === 1) {
                    $this->assertEquals(':password', $param);
                    $this->assertEquals($newPassword, $value);
                    $this->assertEquals(PDO::PARAM_STR, $type);
                } elseif ($count === 2) {
                    $this->assertEquals(':id', $param);
                    $this->assertEquals($id, $value);
                    $this->assertEquals(PDO::PARAM_INT, $type);
                }
                return true;
            }
        );

        $this->stmtMock->expects($this->once())->method('execute');

        $this->userRepository->updateUserPassword($id, $newPassword);
    }



}