<?php

use App\Helpers\ReviewHelper;
use App\Repository\RestaurantRepository;
use App\Repository\ReviewRepository;
use App\Services\FileService;
use App\Services\ValidatorService;
use PHPUnit\Framework\TestCase;
use App\Controllers\RestaurantController;
use App\Models\Restaurant;
use App\Models\Address;
use App\Utils\Auth;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;

class RestaurantControllerTest extends TestCase {
    private $controller;
    private $restaurantRepoMock;
    private $reviewRepoMock;
    private $sessionMock;
    private $requestMock;
    private $reviewHelperMock;
    private $validatorServiceMock;
    private $fileServiceMock;
    private $authMock;
    private $redirectMock;

    protected function setUp(): void {
        $this->restaurantRepoMock = $this->createMock(RestaurantRepository::class);
        $this->reviewRepoMock = $this->createMock(ReviewRepository::class);
        $this->sessionMock = $this->createMock(Session::class);
        $this->requestMock = $this->createMock(Request::class);
        $this->reviewHelperMock = $this->createMock(ReviewHelper::class);
        $this->validatorServiceMock = $this->createMock(ValidatorService::class);
        $this->fileServiceMock = $this->createMock(FileService::class);
        $this->authMock = $this->createMock(Auth::class);
        $this->redirectMock = $this->createMock(Redirect::class);

        $this->controller = new RestaurantController(
            $this->restaurantRepoMock,
            $this->reviewRepoMock,
            $this->sessionMock,
            $this->requestMock,
            $this->reviewHelperMock,
            $this->validatorServiceMock,
            $this->fileServiceMock,
            $this->authMock,
            $this->redirectMock
        );
    }

    public function testRestaurantDetailsForExistingRestaurant() {
        $restaurantId = 1;
        $restaurant = new Restaurant($restaurantId, "Test Restaurant", "Description", null, "http://example.com", "test@example.com", "1234567890", new Address(1, "Street", "City", "00000", "1", ""));
        $this->restaurantRepoMock->method('getRestaurant')->willReturn($restaurant);
        $this->reviewRepoMock->method('getReviews')->willReturn([]);

        $this->controller->restaurant($restaurantId);

        $this->assertTrue(true);
    }
    public function testRestaurantDetailsForNonExistentRestaurant() {
        $restaurantId = 999;
        $this->restaurantRepoMock->method('getRestaurant')->willReturn(null);
        $this->redirectMock->expects($this->once())->method('to')->with('/error404', [], 404);

        $this->controller->restaurant($restaurantId);


        $this->assertTrue(true);
    }


    public function testDeleteRestaurantAsAdmin() {
        $restaurantId = 1;
        $this->authMock->method('isAdminUser')->willReturn(true);
        $this->restaurantRepoMock->expects($this->once())->method('deleteRestaurant')->with($restaurantId);
        ob_start();
        $this->controller->deleteRestaurant($restaurantId);
        ob_get_clean();
        $this->assertEquals(200, http_response_code());
    }

    public function testDeleteRestaurantAsNonAdmin() {
        $restaurantId = 1;
        $this->authMock->method('isAdminUser')->willReturn(false);
        ob_start();
        $this->controller->deleteRestaurant($restaurantId);
        ob_get_clean();
        $this->assertEquals(401, http_response_code());
    }

    public function testPublicateRestaurantAsAdmin() {
        $restaurantId = 1;
        $this->authMock->method('isAdminUser')->willReturn(true);
        $this->restaurantRepoMock->expects($this->once())->method('togglePublication')->with($restaurantId);
        ob_start();
        $this->controller->publicateRestaurant($restaurantId);
        ob_get_clean();
        $this->assertEquals(200, http_response_code());
    }

    public function testPublicateRestaurantAsNonAdmin() {
        $restaurantId = 1;
        $this->authMock->method('isAdminUser')->willReturn(false);
        ob_start();
        $this->controller->publicateRestaurant($restaurantId);
        ob_get_clean();
        $this->assertEquals(401, http_response_code());
    }

}
