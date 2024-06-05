<?php

namespace App\Controllers;

use App\Helpers\ReviewHelper;
use App\Helpers\ReviewHelperI;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantRepositoryI;

class HomepageController extends AppController {

    private RestaurantRepositoryI $restaurantRepository;
    private ReviewHelperI $reviewHelper;

    public function __construct(RestaurantRepositoryI $restaurantRepository, ReviewHelperI $reviewHelper)
    {
        parent::__construct();
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