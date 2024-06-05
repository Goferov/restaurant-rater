<?php

namespace App\Controllers;

use App\Config;
use App\Helpers\ReviewHelper;
use App\Helpers\ReviewHelperI;
use App\Models\Address;
use App\Models\Restaurant;
use App\Models\Review;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantRepositoryI;
use App\Repository\ReviewRepository;
use App\Repository\ReviewRepositoryI;
use App\Request;
use App\Session;

class RestaurantController extends AppController {

    const MAX_FILE_SIZE = 1024*1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';
    private array $messages = [];
    private RestaurantRepositoryI $restaurantRepository;
    private ReviewRepositoryI $reviewRepository;
    private Session $session;
    private Request $request;
    private ReviewHelperI $reviewHelper;
    private array $messagesList;

    public function __construct(
        RestaurantRepositoryI $restaurantRepository,
        ReviewRepositoryI $reviewRepository,
        Session $session,
        Request $request,
        ReviewHelperI $reviewHelper
    ) {
        parent::__construct();
        $this->restaurantRepository = $restaurantRepository;
        $this->reviewRepository = $reviewRepository;
        $this->session = $session;
        $this->request = $request;
        $this->reviewHelper = $reviewHelper;

        $this->messagesList = Config::get('messages');
    }

    public function restaurant($restaurantId = null) {
        if($restaurantId) {
            $messageKey = $this->request->get('message');
            $success = $this->request->get('success');
            $reviewData = $this->session->get('reviewData');

            $restaurant = $this->restaurantRepository->getRestaurant($restaurantId);
            $reviewList = $this->reviewRepository->getReviews($restaurantId);

            if(!$restaurant) {
                $this->redirect('/error404', [], 404);
            }

            $this->render('details', [
                'restaurant' => $restaurant,
                'image' => $restaurant->getImage() ? '/public/uploads/' . $restaurant->getImage() : '/public/img/placeholder.png',
                'message' => $this->messagesList[$messageKey] ?? null,
                'success' => $success,
                'lastReview' => $reviewData['review'] ?? null,
                'reviewList' => $reviewList,
                'stars' => $this->reviewHelper->generateStars($restaurant->getRate())
            ]);
        }
        else {
            $loggedUser = $this->session->get('userSession');
            $this->render('list', [
                'restaurants' => $this->restaurantRepository->getRestaurants(),
                'roleId' => $loggedUser['roleId'] ?? null,
                'reviewHelper' => $this->reviewHelper,
            ]);
        }
    }

    public function addRestaurant($id = null) {
        $this->checkUserSessionAndRole();

        $this->render('addRestaurant', [
            'restaurant' => $id ? $this->restaurantRepository->getRestaurant($id) : null,
            'messages' => $this->loadMessages($this->request->get('messages')),
            'success' => $this->messagesList[$this->request->get('success')] ?? null,
        ]);
    }

    public function saveRestaurant($id = null) {
        $this->checkUserSessionAndRole();

        $restaurantData = [
            'name' => $this->request->post('name'),
            'description' => $this->request->post('description', ''),
            'website' => $this->request->post('website', ''),
            'email' => filter_var($this->request->post('email', ''), FILTER_SANITIZE_EMAIL),
            'phone' => $this->request->post('phone', ''),
            'street' => $this->request->post('street'),
            'city' => $this->request->post('city'),
            'postalCode' => $this->request->post('postalCode'),
            'houseNo' => $this->request->post('houseNo'),
        ];
        $deleteFile = $this->request->post('delete_file');

        $address = new Address(
            (int)$this->request->post('addressId'),
            $this->request->post('street'),
            $this->request->post('city'),
            $this->request->post('postalCode'),
            $this->request->post('houseNo'),
            $this->request->post('apartmentNo', '')
        );

        $fileData = $this->request->file('file');

        if(!$this->validateRestaurantData($restaurantData) || !$this->validateRestaurantFile($fileData)) {
            $this->redirect('/addRestaurant/' . $id, ['messages' => json_encode($this->messages)]);
        }

        $newFileName = null;

        if (!$deleteFile && $fileData && is_uploaded_file($fileData['tmp_name'])) {
            $newFileName = $this->generateUniqueFilename($fileData['name']);
            move_uploaded_file(
                $fileData['tmp_name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$newFileName
            );
        }

        if ($id && !$deleteFile) {
            $existingRestaurant = $this->restaurantRepository->getRestaurant($id);
            $restaurantImage = $newFileName ?: $existingRestaurant->getImage();
        } else {
            $restaurantImage = $newFileName;
        }

        if($deleteFile) {
            $restaurantImage = '';
        }

        $restaurant = new Restaurant(
            (int)$id,
            $restaurantData['name'],
            $restaurantData['description'],
            $restaurantImage,
            $restaurantData['website'],
            $restaurantData['email'],
            $restaurantData['phone'],
            $address
        );

        if ($id) {
            $this->restaurantRepository->updateRestaurant($restaurant);
        } else {
            $id = $this->restaurantRepository->addRestaurant($restaurant);
        }
        $this->redirect('/addRestaurant/' . $id, ['success' => 'restaurantAdded']);
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
        if ($this->isApplicationJson()) {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            header('Content-type: application/json');
            http_response_code(200);
            echo json_encode($this->restaurantRepository->getRestaurantByFilters($decoded['search'], $decoded['order']));
        }
    }

    public function deleteRestaurant(int $id): void {
        if($this->isAdminUser()) {
            $this->restaurantRepository->deleteRestaurant($id);
            http_response_code(200);
        }
        else {
            http_response_code(401);
        }
    }

    public function publicateRestaurant(int $id): void {
        if($this->isAdminUser()) {
            $this->restaurantRepository->togglePublication($id);
            http_response_code(200);
        }
        else {
            http_response_code(401);
        }
    }

    private function checkUserSessionAndRole() {
        if(!$this->isAdminUser()) {
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

    private function validateRestaurantData($data) {
        $isValid = true;

        $isValid &= $this->checkRequiredFields($data, ['name', 'street', 'city', 'postalCode', 'houseNo']);
        $isValid &= $this->validateEmail($data['email']);
        $isValid &= $this->validateURL($data['website']);
        $isValid &= $this->validatePostalCode($data['postalCode']);
        $isValid &= $this->validatePhoneNumber($data['phone']);

        return $isValid;
    }

    private function checkRequiredFields($data, $requiredFields) {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->addErrorMessage('requiredFields');
                return false;
            }
        }
        return true;
    }

    private function validateEmail($email) {
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addErrorMessage('wrongEmail');
            return false;
        }
        return true;
    }

    private function validateURL($url) {
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            $this->addErrorMessage('wrongUrl');
            return false;
        }
        return true;
    }

    private function validatePostalCode($postalCode) {
        if (!preg_match('/^[0-9]{2}-?[0-9]{3}$/Du', $postalCode)) {
            $this->addErrorMessage('invalidPostalCode');
            return false;
        }
        return true;
    }

    private function validatePhoneNumber($phoneNumber) {
        if (!empty($phoneNumber) && !preg_match('/^\d+$/', $phoneNumber)) {
            $this->addErrorMessage('invalidPhoneNumber');
            return false;
        }
        return true;
    }

    private function validateRestaurantFile($file) {
        if($file['error']) {
            return true;
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->addErrorMessage('fileTooLarge');
            return false;
        }

        if (!in_array($file['type'], self::SUPPORTED_TYPES)) {
            $this->addErrorMessage('wrongFileExtension');
            return false;
        }

        return true;
    }

    private function addErrorMessage($message) {
        $this->messages[] = $message;
    }

    private function loadMessages($messages) {
        $messagesToReturn = [];
        if($messages && $messages = json_decode($messages)) {
            foreach ($messages as $message) {
                $messagesToReturn[] .= $this->messagesList[$message];
            }
        }
        return $messagesToReturn;
    }
}