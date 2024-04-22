<?php

namespace App\Repository;

use App\Models\Review;
use PDO;

class ReviewRepository extends Repository {
    public function getReviews(int $restaurantId) {
        $result = array();
        $sql = '
        SELECT 
            review_id, restaurant_id, user_id, rate, review, name
        FROM public.is_review r INNER JOIN public.is_user u ON u.user_id = r.user_id
        WHERE status = true AND publicate = true AND restaurant_id = :restaurant_id
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
                $review['create_at'],
                $review['name'],
                $review['user_id'],
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


}