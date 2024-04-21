<?php

namespace App\Repository;
use PDO;
use App\Models\Restaurant;
use App\Models\Address;

class RestaurantRepository extends Repository {
    public function addRestaurant(Restaurant $restaurant)
    {
        $dbh = $this->database->connect();
        $stmt = $dbh->prepare
        ('
            INSERT INTO public.is_address (street, city, postal_code, house_no, apartment_no) VALUES (?, ?, ?, ?, ?)
        ');

        $dbh->beginTransaction();
        $stmt->execute([
            $restaurant->getAddress()->getStreet(),
            $restaurant->getAddress()->getCity(),
            $restaurant->getAddress()->getPostalCode(),
            $restaurant->getAddress()->getHouseNo(),
            $restaurant->getAddress()->getApartmentNo(),
        ]);
        $dbh->commit();

        $addressId = $dbh->lastInsertId();
        $stmt = $this->database->connect()->prepare
        ('
            INSERT INTO public.is_restaurant (address_id, name, description, image, email, website) VALUES (?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $addressId,
            $restaurant->getName(),
            $restaurant->getDescription(),
            $restaurant->getImage(),
            $restaurant->getEmail(),
            $restaurant->getWebsite(),
        ]);
    }
}