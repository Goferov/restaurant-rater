<?php

namespace App\Controllers;

class RestaurantController extends AppController {
    public function restaurant($restaurantId = null) {
        if($restaurantId) {
            return $this->render('details');
        }
        $this->render('list');
    }

    public function addRestaurant() {
        $this->render('addRestaurant');
    }
}