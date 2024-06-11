<?php

use PHPUnit\Framework\TestCase;
use App\Utils\Request;

class RequestTest extends TestCase {
    private Request $request;

    protected function setUp(): void {
        $get = ['id' => '123', 'type' => 'user'];
        $post = ['name' => 'John', 'password' => 'password123'];
        $server = ['REQUEST_METHOD' => 'POST', 'HTTP_HOST' => 'example.com'];
        $file = ['photo' => ['name' => 'image.png', 'size' => 2100]];

        $this->request = new Request($get, $post, $server, $file);
    }

    public function testIsPost() {
        $this->assertTrue($this->request->isPost());
    }

    public function testIsGet() {
        $this->assertFalse($this->request->isGet());
    }

    public function testGetParameter() {
        $this->assertEquals('123', $this->request->get('id'));
        $this->assertNull($this->request->get('nonexistent'));
        $this->assertEquals('default', $this->request->get('nonexistent', 'default'));
    }

    public function testPostParameter() {
        $this->assertEquals('John', $this->request->post('name'));
        $this->assertNull($this->request->post('age'));
        $this->assertEquals('default', $this->request->post('age', 'default'));
    }

    public function testServerParameter() {
        $this->assertEquals('POST', $this->request->server('REQUEST_METHOD'));
        $this->assertNull($this->request->server('nonexistent'));
    }

    public function testFileParameter() {
        $this->assertIsArray($this->request->file('photo'));
        $this->assertEquals('image.png', $this->request->file('photo')['name']);
        $this->assertNull($this->request->file('document'));
    }
}
