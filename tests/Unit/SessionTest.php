<?php


use PHPUnit\Framework\TestCase;
use App\Utils\Session;

class SessionTest extends TestCase {
    private Session $session;

    protected function setUp(): void {
        $_SESSION = [];
        $this->session = new Session();
    }

    protected function tearDown(): void {
        $_SESSION = [];
    }

    public function testSetAndGetSession() {
        $this->session->set('test', 'value');
        $this->assertEquals('value', $this->session->get('test'));
    }

    public function testGetNonExistentKey() {
        $this->assertNull($this->session->get('non_existent_key'));
    }

    public function testDefaultValueForNonExistentKey() {
        $defaultValue = 'default';
        $this->assertEquals($defaultValue, $this->session->get('non_existent', $defaultValue));
    }

    public function testExistenceOfSessionKey() {
        $this->assertFalse($this->session->exists('test'));
        $this->session->set('test', 'value');
        $this->assertTrue($this->session->exists('test'));
    }

    public function testRemoveSessionKey() {
        $this->session->set('test', 'value');
        $this->assertTrue($this->session->exists('test'));
        $this->session->remove('test');
        $this->assertFalse($this->session->exists('test'));
    }

}
