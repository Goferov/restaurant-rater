<?php

namespace App\Controllers;

use App\Helpers\ReviewHelper;
use App\Repository\RestaurantRepository;

class HomepageController extends AppController {
    public function index(): void {
        $this->render('homepage', [
            'restaurants' => (new RestaurantRepository())->getRestaurants(limit: 3),
            'reviewHelper' => new ReviewHelper(),
        ]);
    }
}