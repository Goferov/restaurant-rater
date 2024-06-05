<?php
namespace App\Controllers;

use App\Config;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantRepositoryI;
use App\Request;
use App\Session;

class PanelController extends AppController {

    private ?array $userSession;
    private array $variables;
    private Session $session;
    private Request $request;
    private RestaurantRepositoryI $restaurantRepository;

    public function  __construct(Session $session, Request $request, RestaurantRepositoryI $restaurantRepository) {
        parent::__construct();
        $this->session = $session;
        $this->request = $request;
        $this->restaurantRepository = $restaurantRepository;
        $this->userSession = $this->session->get('userSession');
        $this->variables['isAdmin'] = $this->isAdminUser();
    }

    public function panel() {
        if($this->userSession) {
            $messagesList = Config::get('messages');
            $messageKey = $this->request->get('message');
            $this->variables['message'] = $messagesList[$messageKey] ?? null;
            $this->render('panel', $this->variables);
        }
        else {
            $this->redirect('/');
        }
    }

    public function restaurantList() {
        if(!$this->variables['isAdmin']) {
            $this->redirect('/');
        }
        $this->variables['restaurants'] = $this->restaurantRepository->getRestaurants(false);
        $this->render('restaurantListPanel', $this->variables);
    }
}