<?php

namespace App\Repository;
use PDO;
use App\Models\Restaurant;
use App\Models\Address;

class RestaurantRepository extends Repository {

    public function getRestaurants($limit = null) {
        $result = array();
        $sql = '
SELECT r.restaurant_id, r.name, r.description, r.image, r.email, r.phone, r.website, a.address_id, a.street, a.city, a.postal_code, a.house_no, a.apartment_no, AVG(re.rate) as rate
FROM public.is_restaurant r 
INNER JOIN public.is_address a ON r.address_id = a.address_id 
LEFT JOIN public.is_review re ON r.restaurant_id = re.restaurant_id
GROUP BY r.restaurant_id, r.name, r.description, r.image, r.email, r.phone, r.website, a.address_id, a.street, a.city, a.postal_code, a.house_no, a.apartment_no
ORDER BY r.restaurant_id DESC;
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
                $address,
                $restaurant['rate'] ?? 0
            );
        }
        return $result;
    }

    public function getRestaurant($id) {
        $sql = '
        SELECT 
            r.restaurant_id, name, description, image, email, phone, website, a.address_id, street, city, postal_code, house_no, apartment_no, AVG(re.rate) as rate
        FROM public.is_restaurant r 
        INNER JOIN public.is_address a ON r.address_id = a.address_id 
        LEFT JOIN 
        public.is_review re ON r.restaurant_id = re.restaurant_id
        WHERE  r.restaurant_id = :id
        GROUP BY 
        r.restaurant_id, 
        r.name, 
        r.description, 
        r.image, 
        r.email, 
        r.phone, 
        r.website, 
        a.address_id, 
        a.street, 
        a.city, 
        a.postal_code, 
        a.house_no, 
        a.apartment_no
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
            $address,
            $restaurantData['rate']
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
        return $dbh->lastInsertId();

    }

    public function getRestaurantByFilters($searchString, $orderBy = null) {
        $searchString = '%' . strtolower($searchString) . '%';
        $sql = '
SELECT r.restaurant_id, r.name, r.description, r.image, r.email, r.phone, r.website, a.address_id, a.street, a.city, a.postal_code, a.house_no, a.apartment_no, avg(coalesce(re.rate, 0)) as rate
FROM public.is_restaurant r 
INNER JOIN public.is_address a ON r.address_id = a.address_id 
LEFT JOIN public.is_review re ON r.restaurant_id = re.restaurant_id
WHERE LOWER(name) LIKE :search OR LOWER(description) LIKE :search OR LOWER(city) LIKE :search
GROUP BY r.restaurant_id, r.name, r.description, r.image, r.email, r.phone, r.website, a.address_id, a.street, a.city, a.postal_code, a.house_no, a.apartment_no
ORDER BY ' . $this->getOrderByClause($orderBy) . ';
';

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':search', $searchString, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteRestaurant($id) {
        $sql = '
        UPDATE public.is_restaurant
        SET status = false
        WHERE restaurant_id = :id
    ';

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function togglePublication($id) {
        $sql = '
        UPDATE public.is_restaurant
        SET publicate = NOT publicate
        WHERE restaurant_id = :id
    ';

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateRestaurant(Restaurant $restaurant) {
        $street = $restaurant->getAddress()->getStreet();
        $city = $restaurant->getAddress()->getCity();
        $postalCode = $restaurant->getAddress()->getPostalCode();
        $houseNo = $restaurant->getAddress()->getHouseNo();
        $apartmentNo = $restaurant->getAddress()->getApartmentNo();
        $addressId = $restaurant->getAddress()->getId();

        $sql = '
        UPDATE public.is_address
        SET street = :street, city = :city, postal_code = :postalCode, house_no = :houseNo, apartment_no = :apartmentNo
        WHERE address_id = :addressId
    ';

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':street', $street, PDO::PARAM_STR);
        $stmt->bindParam(':city', $city, PDO::PARAM_STR);
        $stmt->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
        $stmt->bindParam(':houseNo', $houseNo, PDO::PARAM_STR);
        $stmt->bindParam(':apartmentNo', $apartmentNo, PDO::PARAM_STR);
        $stmt->bindParam(':addressId', $addressId, PDO::PARAM_INT);
        $stmt->execute();

        $name = $restaurant->getName();
        $description = $restaurant->getDescription();
        $image = $restaurant->getImage();
        $email = $restaurant->getEmail();
        $phone = $restaurant->getPhone();
        $website = $restaurant->getWebsite();
        $restaurantId = $restaurant->getId();

        $sql = '
        UPDATE public.is_restaurant
        SET name = :name, description = :description, image = :image, email = :email, phone = :phone, website = :website
        WHERE restaurant_id = :restaurantId
    ';

        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':website', $website, PDO::PARAM_STR);
        $stmt->bindParam(':restaurantId', $restaurantId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function getOrderByClause($orderBy) {
        switch ($orderBy) {
            case '1': // Od najnowszych
                return 'r.restaurant_id DESC';
            case '2': // Od najstarszych
                return 'r.restaurant_id ASC';
            case '3': // Od A do Z
                return 'r.name ASC';
            case '4': // Od Z do A
                return 'r.name DESC';
            case '5': // Od najlepszych
                return 'rate DESC';
            case '6': // Od najgorszych
                return 'rate ASC';
            default:
                return 'r.restaurant_id DESC';
        }
    }
}