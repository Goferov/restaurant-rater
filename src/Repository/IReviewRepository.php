<?php

namespace App\Repository;

use App\Models\Review;

interface IReviewRepository
{
    public function getReviews(int $restaurantId): array;
    public function addReview(Review $review);
    public function getUserRestaurantReview(int $restaurantId, int $userId): ?Review;
}