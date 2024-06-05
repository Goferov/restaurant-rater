<?php

namespace App\Controllers;

use App\Helpers\ReviewHelper;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantRepositoryI;

class HomepageController extends AppController {

    private RestaurantRepositoryI $restaurantRepository;

    public function __construct(RestaurantRepositoryI $restaurantRepository)
    {
        parent::__construct();
        $this->restaurantRepository = $restaurantRepository;
    }

    public function index(): void {
        $this->render('homepage', [
            'restaurants' => $this->restaurantRepository->getRestaurants(limit: 3),
            'reviewHelper' => new ReviewHelper(),
        ]);
    }
}