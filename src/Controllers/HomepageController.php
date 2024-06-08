<?php

namespace App\Controllers;

use App\Helpers\IReviewHelper;
use App\Repository\IRestaurantRepository;

class HomepageController extends AppController {

    private IRestaurantRepository $restaurantRepository;
    private IReviewHelper $reviewHelper;

    public function __construct(IRestaurantRepository $restaurantRepository, IReviewHelper $reviewHelper)
    {
        $this->restaurantRepository = $restaurantRepository;
        $this->reviewHelper = $reviewHelper;
    }

    public function index(): void {
        $this->render('homepage', [
            'restaurants' => $this->restaurantRepository->getRestaurants(limit: 3),
            'reviewHelper' => $this->reviewHelper,
        ]);
    }
}