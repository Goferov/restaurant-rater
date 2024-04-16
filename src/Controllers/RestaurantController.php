<?php

namespace App\Controllers;

class RestaurantController extends AppController {
    public function restaurant($restaurantId = null) {
        if($restaurantId) {
            return $this->render('details');
        }
        $this->render('list');
    }


}