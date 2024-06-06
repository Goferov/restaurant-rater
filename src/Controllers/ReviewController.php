<?php

namespace App\Controllers;

use App\Models\Review;
use App\Repository\ReviewRepositoryI;
use App\Request;
use App\Session;

class ReviewController extends AppController
{
    private ReviewRepositoryI $reviewRepository;
    private Session $session;
    private Request $request;

    public function __construct(ReviewRepositoryI $reviewRepository, Session $session, Request $request) {
        parent::__construct();
        $this->reviewRepository = $reviewRepository;
        $this->session = $session;
        $this->request = $request;
    }


    public function saveReview($id = null) {
        $loggedUser = $this->session->get('userSession');
        $redirect = $this->getPreviousPage();

        $rate = (int)$this->request->post('rate');
        $review = $this->request->post('review');
        $restaurant_id = (int)$this->request->post('restaurant_id');
        $this->session->set('reviewData', ['rate' => $rate, 'review' => $review]);

        if(!$loggedUser || !isset($loggedUser['id'])) {
            $this->redirect($redirect, ['loginMessage' => 'mustLogin']);
        }

        if ($rate < 1 || $rate > 5)  {
            $this->redirect($redirect, ['message' => 'opinionScope']);
        }

        if (empty($review) || strlen($review) > 255)  {
            $this->redirect($redirect, ['message' => 'reviewIsEmpty']);
        }

        $userId = (int)$loggedUser['id'];
        $userReview = $this->reviewRepository->getUserRestaurantReview($restaurant_id, $userId);
        if($userReview) {
            $this->redirect($redirect, ['message' => 'reviewExists']);
        }

        $review = new Review(null, $restaurant_id, $rate, $review, $userId);

        $this->session->remove('reviewData');
        $this->reviewRepository->addReview($review);
        $this->redirect($redirect, ['message' => 'addedOpinion', 'success' => true]);
    }
}