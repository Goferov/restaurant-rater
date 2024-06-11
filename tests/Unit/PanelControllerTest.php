<?php

namespace App\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\PanelController;
use App\Repository\IRestaurantRepository;
use App\Utils\Auth;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;

class PanelControllerTest extends TestCase {
    private $requestMock;
    private $restaurantRepositoryMock;
    private $authMock;
    private $redirectMock;
    private $panelController;

    protected function setUp(): void {
        $this->requestMock = $this->createMock(Request::class);
        $this->restaurantRepositoryMock = $this->createMock(IRestaurantRepository::class);
        $this->authMock = $this->createMock(Auth::class);
        $this->redirectMock = $this->createMock(Redirect::class);

        $this->panelController = new PanelController(
            $this->requestMock,
            $this->restaurantRepositoryMock,
            $this->authMock,
            $this->redirectMock
        );
    }

    public function testPanelAccessForLoggedInUser() {
        $this->authMock->method('isLoggedUser')->willReturn(true);
        $this->requestMock->method('get')->willReturn('successMessageKey');

        ob_start();
        $this->panelController->panel();
        $output = ob_get_clean();

        $this->assertNotEmpty($output);
    }

    public function testPanelRedirectForNonLoggedInUser() {
        $this->authMock->method('isLoggedUser')->willReturn(false);
        $this->redirectMock->expects($this->once())->method('to')->with('/');

        $this->panelController->panel();
    }

    public function testRestaurantListAccessForAdmin() {
        $this->authMock->method('isAdminUser')->willReturn(true);
        $this->restaurantRepositoryMock->method('getRestaurants')->willReturn([]);

        ob_start();
        $this->panelController->restaurantList();
        $output = ob_get_clean();

        $this->assertNotEmpty($output);
    }

    public function testRestaurantListRedirectForNonAdmin() {
        $this->authMock->method('isAdminUser')->willReturn(false);
        $this->redirectMock->expects($this->once())->method('to')->with('/');

        $this->panelController->restaurantList();
    }
}
