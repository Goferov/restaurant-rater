<?php

use PHPUnit\Framework\TestCase;
use App\Utils\Auth;
use App\Utils\Session;

class AuthTest extends TestCase {
    private Session $session;
    private Auth $auth;

    protected function setUp(): void {
        $this->session = $this->createMock(Session::class);
        $this->auth = new Auth($this->session);
    }

    public function testIsAdminUserWithAdminRole() {
        $this->session->method('get')->willReturn(['roleId' => 1]);
        $this->assertTrue($this->auth->isAdminUser());
    }

    public function testIsAdminUserWithNonAdminRole() {
        $this->session->method('get')->willReturn(['roleId' => 2]);
        $this->assertFalse($this->auth->isAdminUser());
    }

    public function testIsAdminUserWithNoUserSession() {
        $this->session->method('get')->willReturn(null);
        $this->assertFalse($this->auth->isAdminUser());
    }

    public function testIsLoggedUserWhenLogged() {
        $this->session->method('get')->willReturn(['userId' => 123]);
        $this->assertTrue($this->auth->isLoggedUser());
    }

    public function testIsLoggedUserWhenNotLogged() {
        $this->session->method('get')->willReturn(null);
        $this->assertFalse($this->auth->isLoggedUser());
    }
}
