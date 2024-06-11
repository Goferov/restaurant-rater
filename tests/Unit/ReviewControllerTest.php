<?php


use PHPUnit\Framework\TestCase;
use App\Controllers\ReviewController;
use App\Models\Review;
use App\Repository\IReviewRepository;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;

class ReviewControllerTest extends TestCase {
    private $reviewRepositoryMock;
    private $sessionMock;
    private $requestMock;
    private $redirectMock;
    private $reviewController;

    protected function setUp(): void {
        $this->reviewRepositoryMock = $this->createMock(IReviewRepository::class);
        $this->sessionMock = $this->createMock(Session::class);
        $this->requestMock = $this->createMock(Request::class);
        $this->redirectMock = $this->createMock(Redirect::class);
        $this->redirectMock->method('getPreviousPage')->willReturn('/default-redirect-url');

        $this->reviewController = new ReviewController(
            $this->reviewRepositoryMock,
            $this->sessionMock,
            $this->requestMock,
            $this->redirectMock
        );
    }

    public function testSaveReviewNotLoggedIn() {
        $this->sessionMock->method('get')->willReturn(null);
        $this->redirectMock->expects($this->once())->method('to')
            ->with($this->anything(), ['loginMessage' => 'mustLogin']);

        $this->reviewController->saveReview();
    }

    public function testSaveReviewInvalidRate() {
        $this->sessionMock->method('get')->willReturn(['id' => 1]);
        $this->requestMock->method('post')->willReturnMap([
            ['rate', null, 6],
            ['review', null, 'Nice place!'],
            ['restaurant_id', null, 101]
        ]);

        $this->redirectMock->expects($this->once())->method('to')
            ->with($this->anything(), ['message' => 'opinionScope']);

        $this->reviewController->saveReview();
    }

    public function testSaveReviewSuccess() {
        $this->sessionMock->method('get')->willReturn(['id' => 1]);
        $this->requestMock->method('post')->willReturnMap([
            ['rate', null, 5],
            ['review', null, 'Great food!'],
            ['restaurant_id', null, 101]
        ]);
        $this->reviewRepositoryMock->method('getUserRestaurantReview')->willReturn(null);
        $this->reviewRepositoryMock->expects($this->once())->method('addReview')->with($this->isInstanceOf(Review::class));
        $this->redirectMock->expects($this->once())->method('to')
            ->with($this->anything(), ['message' => 'addedOpinion', 'success' => true]);

        $this->reviewController->saveReview();
    }

}
