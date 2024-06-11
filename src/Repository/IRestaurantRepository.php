<?php

namespace App\Repository;

use App\Models\Restaurant;

interface IRestaurantRepository
{
    public function getRestaurants($publicate = true, $limit = null);
    public function getRestaurant($id, $show_only_publicate = true);
    public function addRestaurant(Restaurant $restaurant);
    public function getRestaurantByFilters($searchString, $orderBy = null);
    public function deleteRestaurant($id);
    public function togglePublication($id);
    public function updateRestaurant(Restaurant $restaurant);
}