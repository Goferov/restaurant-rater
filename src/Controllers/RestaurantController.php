<?php

namespace App\Controllers;

use App\Config;
use App\Helpers\IReviewHelper;
use App\Models\Address;
use App\Models\Restaurant;
use App\Repository\IRestaurantRepository;
use App\Repository\IReviewRepository;
use App\Services\ValidatorService;
use App\Utils\Auth;
use App\Utils\IFile;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;

class RestaurantController extends AppController {

    private array $messages = [];
    private IRestaurantRepository $restaurantRepository;
    private IReviewRepository $reviewRepository;
    private Session $session;
    private Request $request;
    private IReviewHelper $reviewHelper;
    private ValidatorService $validatorService;
    private IFile $fileService;
    private Auth $authService;
    private Redirect $redirect;
    private array $messagesList;

    public function __construct(
        IRestaurantRepository $restaurantRepository,
        IReviewRepository     $reviewRepository,
        Session               $session,
        Request               $request,
        IReviewHelper         $reviewHelper,
        ValidatorService      $validatorService,
        IFile                 $fileService,
        Auth                  $authService,
        Redirect              $redirect,
    ) {
        $this->restaurantRepository = $restaurantRepository;
        $this->reviewRepository = $reviewRepository;
        $this->session = $session;
        $this->request = $request;
        $this->reviewHelper = $reviewHelper;
        $this->validatorService = $validatorService;
        $this->fileService = $fileService;
        $this->authService = $authService;
        $this->redirect = $redirect;

        $this->messagesList = Config::get('messages');
    }

    public function restaurant($id = null) {
        $restaurantId = $id;
        if($restaurantId) {
            $messageKey = $this->request->get('message');
            $success = $this->request->get('success');
            $reviewData = $this->session->get('reviewData');

            $restaurant = $this->restaurantRepository->getRestaurant($restaurantId);
            $reviewList = $this->reviewRepository->getReviews($restaurantId);

            if(!$restaurant) {
                $this->redirect->to('/error404', [], 404);
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
        if(!$this->authService->isAdminUser()) {
            $this->redirect->to('/');
        }

        $this->render('addRestaurant', [
            'restaurant' => $id ? $this->restaurantRepository->getRestaurant($id) : null,
            'messages' => $this->validatorService->getMessageStorage()->loadMessagesFromConfig($this->request->get('messages')),
            'success' => $this->messagesList[$this->request->get('success')] ?? null,
        ]);
    }

    public function saveRestaurant($id = null) {
        if(!$this->authService->isAdminUser()) {
            $this->redirect->to('/');
        }

        $restaurantData = $this->getRestaurantDataFromRequest();
        $deleteFile = $this->request->post('delete_file');

        $address = $this->createAddressFromRequest();

        $fileData = $this->request->file('file');

        if(!$this->validateRestaurantData($restaurantData)) {
            $this->redirect->to('/addRestaurant/' . $id, ['messages' => json_encode($this->validatorService->getMessages())]);
        }

        $newFileName = null;

        if (!$deleteFile && $fileData && is_uploaded_file($fileData['tmp_name'])) {
            if(!$newFileName = $this->fileService->uploadFile($fileData)) {
                $this->redirect->to('/addRestaurant/' . $id, ['messages' => json_encode(['fileError'])]);
            }
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

        $restaurant = $this->createRestaurant($id, $restaurantData, $restaurantImage, $address);

        if ($id) {
            $this->restaurantRepository->updateRestaurant($restaurant);
        } else {
            $id = $this->restaurantRepository->addRestaurant($restaurant);
        }
        $this->redirect->to('/addRestaurant/' . $id, ['success' => 'restaurantAdded']);
    }


    public function search() {
        $contentType = $this->request->server('CONTENT_TYPE') ? trim($this->request->server('CONTENT_TYPE')) : '';

        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            header('Content-type: application/json');
            http_response_code(200);
            echo json_encode($this->restaurantRepository->getRestaurantByFilters($decoded['search'], $decoded['order']));
        }
    }

    public function deleteRestaurant(int $id): void {
        if($this->authService->isAdminUser()) {
            $this->restaurantRepository->deleteRestaurant($id);
            http_response_code(200);
        }
        else {
            http_response_code(401);
        }
    }

    public function publicateRestaurant(int $id): void {
        if($this->authService->isAdminUser()) {
            $this->restaurantRepository->togglePublication($id);
            http_response_code(200);
        }
        else {
            http_response_code(401);
        }
    }

    private function getRestaurantDataFromRequest(): array
    {
        return [
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
    }

    private function createAddressFromRequest(): Address
    {
        return new Address(
            (int)$this->request->post('addressId'),
            $this->request->post('street'),
            $this->request->post('city'),
            $this->request->post('postalCode'),
            $this->request->post('houseNo'),
            $this->request->post('apartmentNo', '')
        );
    }

    private function createRestaurant($id, array $restaurantData, ?string $restaurantImage, Address $address): Restaurant
    {
        return new Restaurant(
            (int)$id,
            $restaurantData['name'],
            $restaurantData['description'],
            $restaurantImage,
            $restaurantData['website'],
            $restaurantData['email'],
            $restaurantData['phone'],
            $address
        );
    }

    private function validateRestaurantData($data) {
        $isValid = true;
        $isValid &= $this->validatorService->validate('requiredFields', ['requiredFields' => ['name', 'street', 'city', 'postalCode', 'houseNo'], 'data' => $data]);
        $isValid &= $this->validatorService->validate('email', $data['email']);
        $isValid &= $this->validatorService->validate('url', $data['website']);
        $isValid &= $this->validatorService->validate('postalCode', $data['postalCode']);
        $isValid &= $this->validatorService->validate('phoneNumber', $data['phone']);

        return $isValid;
    }
}