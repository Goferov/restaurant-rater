<?php

namespace App\Repository;

use App\Models\Review;
use PDO;

class ReviewRepository extends Repository implements IReviewRepository {
    public function getReviews(int $restaurantId): array {
        $result = array();
        $sql = '
        SELECT 
            review_id, restaurant_id, r.user_id, rate, review, r.create_at, name
        FROM public.is_review r INNER JOIN public.is_user u ON u.user_id = r.user_id
        WHERE r.status = true AND r.publicate = true AND restaurant_id = :restaurant_id
        ORDER BY create_at
    ';

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reviews as $review) {
            $result[] = new Review(
                $review['review_id'],
                $review['restaurant_id'],
                $review['rate'],
                $review['review'],
                $review['user_id'],
                $review['create_at'],
                $review['name'],
            );
        }
        return $result;
    }

    public function addReview(Review $review) {
        $stmt = $this->database->connect()->prepare
        ('
            INSERT INTO public.is_review (restaurant_id, user_id, rate, review) VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $review->getRestaurantId(),
            $review->getUserId(),
            $review->getRate(),
            $review->getReview()
        ]);
    }

    public function getUserRestaurantReview(int $restaurantId, int $userId): ?Review {
        $stmt = $this->database->connect()->prepare
        ('
        SELECT review_id, restaurant_id, rate, review, r.create_at, r.user_id 
        FROM public.is_review r
        WHERE restaurant_id = :restaurant_id AND r.user_id = :user_id AND status = true
        ');

        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$review)
            return null;

        return new Review
        (
            $review['review_id'],
            $review['restaurant_id'],
            $review['rate'],
            $review['review'],
            $review['user_id'],
            $review['create_at'],
        );
    }


}