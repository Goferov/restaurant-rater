<?php

use PHPUnit\Framework\TestCase;
use App\Utils\Redirect;
use App\Utils\Request;

class RedirectTest extends TestCase {
    private $request;
    private $redirect;

    protected function setUp(): void {
        $this->request = $this->createMock(Request::class);
        $this->redirect = new Redirect($this->request);
    }

    public function testGetPreviousPage() {
        $this->request->method('server')->willReturn('http://example.com/previousPage');

        $previousPage = $this->redirect->getPreviousPage();
        $this->assertEquals('/previousPage', $previousPage);
    }
}
