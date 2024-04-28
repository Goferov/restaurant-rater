<?php

namespace App\Controllers;

use App\Config;
use App\Helpers\ReviewHelper;
use App\Models\Address;
use App\Models\Restaurant;
use App\Models\Review;
use App\Repository\RestaurantRepository;
use App\Repository\ReviewRepository;

class RestaurantController extends AppController {

    const MAX_FILE_SIZE = 1024*1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';
    private array $messages = [];
    private RestaurantRepository $restaurantRepository;
    private ReviewRepository $reviewRepository;

    public function __construct() {
        parent::__construct();
        $this->restaurantRepository = new RestaurantRepository();
        $this->reviewRepository = new ReviewRepository();
    }

    public function restaurant($restaurantId = null) {
        $reviewHelper = new ReviewHelper();

        if($restaurantId) {
            $messagesList = Config::get('messages');
            $messageKey = $this->request->get('message');
            $success = $this->request->get('success');
            $reviewData = $this->session->get('reviewData');

            $restaurant = $this->restaurantRepository->getRestaurant($restaurantId);
            $reviewList = $this->reviewRepository->getReviews($restaurantId);

            $this->render('details', [
                'restaurant' => $restaurant,
                'message' => $messagesList[$messageKey] ?? null,
                'success' => $success,
                'lastRate' => $reviewData['rate'] ?? null,
                'lastReview' => $reviewData['review'] ?? null,
                'reviewList' => $reviewList,
                'stars' => $reviewHelper->generateStars($restaurant->getRate())
            ]);
        }
        else {
            $loggedUser = $this->session->get('userSession');
            $this->render('list', [
                'restaurants' => $this->restaurantRepository->getRestaurants(),
                'roleId' => $loggedUser['roleId'] ?? null,
                'reviewHelper' => $reviewHelper,
            ]);
        }
    }

    public function addRestaurant($id = null) {
        $this->checkUserSessionAndRole();
        $this->render('addRestaurant', ['restaurant' => $id ? $this->restaurantRepository->getRestaurant($id) : null]);
    }

    public function saveRestaurant($id = null) {
        $this->checkUserSessionAndRole();
        $fileData = $this->request->file('file');

        if(
            $this->request->isPost()
            && $fileData
             // && is_uploaded_file($fileData['tmp_name'])
            ) { // && $this->validateRestaurantData($_POST, $fileData)

            $newFileName = $this->generateUniqueFilename($fileData['name']);
            move_uploaded_file
            (
                $fileData['tmp_name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$newFileName
            );
            $address = new Address(
                $this->request->post('addressId'),
                $this->request->post('street'),
                $this->request->post('city'),
                $this->request->post('postalCode'),
                $this->request->post('houseNo'),
                $this->request->post('apartmentNo', '')
            );

            $restaurant = new Restaurant(
                $id,
                $this->request->post('name'),
                $this->request->post('description', ''),
                $newFileName,
                $this->request->post('website', ''),
                $this->request->post('email', ''),
                $this->request->post('phone', ''),
                $address
            );

            if($id) {

                $this->restaurantRepository->updateRestaurant($restaurant);
            }
            else {
                $id = $this->restaurantRepository->addRestaurant($restaurant);
            }
        }

        $this->redirect('/addRestaurant/' . $id, $this->messages);
    }

    public function saveReview($id = null) {
        $loggedUser = $this->session->get('userSession');
        $redirect = $this->getPreviousPage();

        $rate = (int)$this->request->post('rate');
        $review = $this->request->post('review');
        $restaurant_id = (int)$this->request->post('restaurant_id');
        $this->session->set('reviewData', ['rate' => $rate, 'review' => $review]);

        if(!$loggedUser || !isset($loggedUser['id'])) {
            $this->redirect($redirect, ['loginMessage' => 'mustLogin']);
        }

        if ($rate < 1 || $rate > 5)  {
            $this->redirect($redirect, ['message' => 'opinionScope']);
        }

        if (empty($review) || strlen($review) > 255)  {
            $this->redirect($redirect, ['message' => 'reviewIsEmpty']);
        }

        $userId = (int)$loggedUser['id'];
        $userReview = $this->reviewRepository->getUserRestaurantReview($restaurant_id, $userId);
        if($userReview) {
            $this->redirect($redirect, ['message' => 'reviewExists']);
        }

        $review = new Review(null, $restaurant_id, $rate, $review, $userId);

        $this->session->remove('reviewData');
        $this->reviewRepository->addReview($review);
        $this->redirect($redirect, ['message' => 'addedOpinion', 'success' => true]);
    }

    public function search() {
        if ($this->isApplicationJson())
        {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            header('Content-type: application/json');
            http_response_code(200);
            echo json_encode($this->restaurantRepository->getRestaurantByFilters($decoded['search'], $decoded['order']));
        }
    }

    public function deleteRestaurant(int $id): void {
        $this->restaurantRepository->deleteRestaurant($id);
        http_response_code(200);
    }

    public function publicateRestaurant(int $id): void {
        $this->restaurantRepository->togglePublication($id, '');
        http_response_code(200);
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