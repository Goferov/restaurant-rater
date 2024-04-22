<?php

namespace App\Repository;
use PDO;
use App\Models\Restaurant;
use App\Models\Address;

class RestaurantRepository extends Repository {

    public function getRestaurants($limit = null) {
        $result = array();
        $sql = '
        SELECT 
            restaurant_id, name, description, image, email, phone, website, a.address_id, street, city, postal_code, house_no, apartment_no 
        FROM public.is_restaurant r 
        INNER JOIN public.is_address a ON r.address_id = a.address_id 
        ORDER BY restaurant_id
    ';

        if (is_numeric($limit)) {
            $sql .= ' LIMIT :limit';
        }

        $stmt = $this->database->connect()->prepare($sql);

        if (is_numeric($limit)) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($restaurants as $restaurant) {
            $address = new Address(
                $restaurant['address_id'],
                $restaurant['street'],
                $restaurant['city'],
                $restaurant['postal_code'],
                $restaurant['house_no'],
                $restaurant['apartment_no']
            );

            $result[] = new Restaurant(
                $restaurant['restaurant_id'],
                $restaurant['name'],
                $restaurant['description'],
                $restaurant['image'],
                $restaurant['website'],
                $restaurant['email'],
                $restaurant['phone'],
                $address
            );
        }
        return $result;
    }

    public function getRestaurant($id) {
        $sql = '
        SELECT 
            restaurant_id, name, description, image, email, phone, website, a.address_id, street, city, postal_code, house_no, apartment_no 
        FROM public.is_restaurant r 
        INNER JOIN public.is_address a ON r.address_id = a.address_id 
        WHERE restaurant_id = :id
    ';

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $restaurantData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$restaurantData) {
            return null;
        }

        $address = new Address(
            $restaurantData['address_id'],
            $restaurantData['street'],
            $restaurantData['city'],
            $restaurantData['postal_code'],
            $restaurantData['house_no'],
            $restaurantData['apartment_no']
        );

        $restaurant = new Restaurant(
            $restaurantData['restaurant_id'],
            $restaurantData['name'],
            $restaurantData['description'],
            $restaurantData['image'],
            $restaurantData['website'],
            $restaurantData['email'],
            $restaurantData['phone'],
            $address
        );

        return $restaurant;
    }


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
            INSERT INTO public.is_restaurant (address_id, name, description, image, email, phone, website) VALUES (?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $addressId,
            $restaurant->getName(),
            $restaurant->getDescription(),
            $restaurant->getImage(),
            $restaurant->getEmail(),
            $restaurant->getPhone(),
            $restaurant->getWebsite(),
        ]);
    }
}