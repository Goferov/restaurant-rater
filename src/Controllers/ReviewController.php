<?php

namespace App\Controllers;

use App\Models\Review;
use App\Repository\ReviewRepositoryI;
use App\Request;
use App\Session;
use App\Utils\Redirect;

class ReviewController extends AppController
{
    private ReviewRepositoryI $reviewRepository;
    private Session $session;
    private Request $request;
    private Redirect $redirect;

    public function __construct(ReviewRepositoryI $reviewRepository, Session $session, Request $request, Redirect $redirect) {
        $this->reviewRepository = $reviewRepository;
        $this->session = $session;
        $this->request = $request;
        $this->redirect = $redirect;
    }


    public function saveReview($id = null) {
        $loggedUser = $this->session->get('userSession');
        $redirect = $this->redirect->getPreviousPage();

        $rate = (int)$this->request->post('rate');
        $review = $this->request->post('review');
        $restaurant_id = (int)$this->request->post('restaurant_id');
        $this->session->set('reviewData', ['rate' => $rate, 'review' => $review]);

        if(!$loggedUser || !isset($loggedUser['id'])) {
            $this->redirect->to($redirect, ['loginMessage' => 'mustLogin']);
        }

        if ($rate < 1 || $rate > 5)  {
            $this->redirect->to($redirect, ['message' => 'opinionScope']);
        }

        if (empty($review) || strlen($review) > 255)  {
            $this->redirect->to($redirect, ['message' => 'reviewIsEmpty']);
        }

        $userId = (int)$loggedUser['id'];
        $userReview = $this->reviewRepository->getUserRestaurantReview($restaurant_id, $userId);
        if($userReview) {
            $this->redirect->to($redirect, ['message' => 'reviewExists']);
        }

        $review = new Review(null, $restaurant_id, $rate, $review, $userId);

        $this->session->remove('reviewData');
        $this->reviewRepository->addReview($review);
        $this->redirect->to($redirect, ['message' => 'addedOpinion', 'success' => true]);
    }
}