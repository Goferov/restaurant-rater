<?php

namespace App\Controllers;

use App\Models\Address;
use App\Models\Restaurant;
use App\Repository\RestaurantRepository;

class RestaurantController extends AppController {

    const MAX_FILE_SIZE = 1024*1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';
    private array $messages = [];
    private RestaurantRepository $restaurantRepository;

    public function __construct() {
        parent::__construct();
        $this->restaurantRepository = new RestaurantRepository();
    }

    public function restaurant($restaurantId = null) {
        if($restaurantId) {
            $this->render('details');
        }
        else {
            $this->render('list');
        }
    }

    public function addRestaurant() {
        $this->checkUserSessionAndRole();
        $this->render('addRestaurant');
    }

    public function saveRestaurant() {
        $this->checkUserSessionAndRole();
        $fileData = $this->request->file('file');

        if(
            $this->request->isPost()
            && $fileData
            && is_uploaded_file($fileData['tmp_name'])
            && $this->validateRestaurantData($_POST, $fileData)) {

            $newFileName = $this->generateUniqueFilename($fileData['name']);
            move_uploaded_file
            (
                $fileData['tmp_name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$newFileName
            );
            $address = new Address(
                null,
                $this->request->post('street'),
                $this->request->post('city'),
                $this->request->post('postalCode'),
                $this->request->post('houseNo'),
                $this->request->post('apartmentNo', '')
            );

            $restaurant = new Restaurant(
                null,
                $this->request->post('name'),
                $this->request->post('description', ''),
                $newFileName,
                $this->request->post('website', ''),
                $this->request->post('email', ''),
                $address
            );

            $this->restaurantRepository->addRestaurant($restaurant);
        }

        $this->redirect('/addRestaurant', $this->messages);
    }

    private function checkUserSessionAndRole() {
        $loggedUser = $this->session->get('userSession');
        if(!$loggedUser || $loggedUser['roleId'] != 1) {
            $this->redirect('/');
        }
    }

    private function generateUniqueFilename($filename) {
        $filePath = dirname(__DIR__) . self::UPLOAD_DIRECTORY . $filename;

        if (!file_exists($filePath)) {
            return $filename;
        }

        $fileInfo = pathinfo($filename);
        $baseName = $fileInfo['filename'];
        $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';

        $counter = 1;
        while (file_exists(dirname(__DIR__) . self::UPLOAD_DIRECTORY . $baseName . "_$counter" . $extension)) {
            $counter++;
        }

        return $baseName . "_$counter" . $extension;
    }

    private function validateRestaurantData($data, $file) {

        $requiredFields = ['name', 'street', 'city', 'postalCode', 'houseNo'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->messages[] = 'Pole ' . $field . ' jest wymagane.';
            }
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->messages[] = 'Podany adres email jest nieprawidłowy.';
        }

        if (!empty($data['website']) && !filter_var($data['website'], FILTER_VALIDATE_URL)) {
            $this->messages[] = 'Podany adres strony internetowej jest nieprawidłowy.';
        }

        if($file['size'] > self::MAX_FILE_SIZE)  {
            $this->messages[] = 'Plik jest zbyt duży';
        }

        if(!isset($file['type']) || !in_array($file['type'], self::SUPPORTED_TYPES))  {
            $this->messages[] = 'Nieodpowiedni typ pliku';
        }

        return !$this->messages;
    }

}