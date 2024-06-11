<?php

use App\Models\Review;
use App\Repository\ReviewRepository;
use PHPUnit\Framework\TestCase;
use App\Database;

class ReviewRepositoryTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $databaseMock;
    private $reviewRepository;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->databaseMock = $this->createMock(Database::class);
        $this->databaseMock->method('connect')->willReturn($this->pdoMock);
        $this->reviewRepository = new ReviewRepository($this->databaseMock);
    }

    public function testGetReviews() {
        $restaurantId = 1;
        $expectedSQL = '
        SELECT 
            review_id, restaurant_id, r.user_id, rate, review, r.create_at, name
        FROM public.is_review r INNER JOIN public.is_user u ON u.user_id = r.user_id
        WHERE r.status = true AND r.publicate = true AND restaurant_id = :restaurant_id
        ORDER BY create_at
    ';

        $this->pdoMock->method('prepare')->with($this->equalTo($expectedSQL))->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())->method('bindParam')->with(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $this->stmtMock->expects($this->once())->method('execute');
        $this->stmtMock->method('fetchAll')->willReturn([
            [
                'review_id' => 1,
                'restaurant_id' => $restaurantId,
                'user_id' => 2,
                'rate' => 5,
                'review' => 'Great!',
                'create_at' => '2021-01-01',
                'name' => 'John Doe'
            ]
        ]);

        $reviews = $this->reviewRepository->getReviews($restaurantId);
        $this->assertCount(1, $reviews);
        $this->assertInstanceOf(Review::class, $reviews[0]);
    }

    public function testAddReview() {
        $review = new Review(null, 1, 5, 'Excellent!', 1, '2020-12-12');

        $expectedSQL = '
            INSERT INTO public.is_review (restaurant_id, user_id, rate, review) VALUES (?, ?, ?, ?)
        ';
        $this->pdoMock->method('prepare')->with($this->equalTo($expectedSQL))->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())->method('execute')->with([
            $review->getRestaurantId(),
            $review->getUserId(),
            $review->getRate(),
            $review->getReview()
        ]);

        $this->reviewRepository->addReview($review);
    }

    public function testGetUserRestaurantReview() {
        $restaurantId = 1;
        $userId = 2;
        $expectedSQL = '
        SELECT review_id, restaurant_id, rate, review, r.create_at, r.user_id 
        FROM public.is_review r
        WHERE restaurant_id = :restaurant_id AND r.user_id = :user_id AND status = true
        ';

        $this->pdoMock->method('prepare')->with($this->equalTo($expectedSQL))->willReturn($this->stmtMock);
        $this->stmtMock->method('execute');
        $this->stmtMock->method('fetch')->willReturn([
            'review_id' => 1,
            'restaurant_id' => 1,
            'rate' => 4,
            'review' => 'Good place',
            'create_at' => '2022-01-01',
            'user_id' => 2
        ]);

        $review = $this->reviewRepository->getUserRestaurantReview($restaurantId, $userId);
        $this->assertInstanceOf(Review::class, $review);
    }
}