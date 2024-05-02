<?php
namespace App\Controllers;

use App\Config;
use App\Repository\RestaurantRepository;

class PanelController extends AppController {

    private ?array $userSession;
    private array $variables;
    public function  __construct() {
        parent::__construct();
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
        $restaurantRepository = new RestaurantRepository();
        $this->variables['restaurants'] = $restaurantRepository->getRestaurants(false);
        $this->render('restaurantListPanel', $this->variables);
    }
}