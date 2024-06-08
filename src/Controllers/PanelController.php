<?php
namespace App\Controllers;

use App\Config;
use App\Repository\IRestaurantRepository;
use App\Utils\Auth;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;

class PanelController extends AppController {

    private array $variables;
    private Session $session;
    private Request $request;
    private IRestaurantRepository $restaurantRepository;
    private Auth $auth;
    private Redirect $redirect;

    public function  __construct(Session $session, Request $request, IRestaurantRepository $restaurantRepository, Auth $auth, Redirect $redirect) {
        $this->session = $session;
        $this->request = $request;
        $this->restaurantRepository = $restaurantRepository;
        $this->auth = $auth;
        $this->redirect = $redirect;
        $this->variables['isAdmin'] = $this->auth->isAdminUser();
    }

    public function panel() {
        if($this->auth->isLoggedUser()) {
            $messagesList = Config::get('messages');
            $messageKey = $this->request->get('message');
            $this->variables['message'] = $messagesList[$messageKey] ?? null;
            $this->render('panel', $this->variables);
        }
        else {
            $this->redirect->to('/');
        }
    }

    public function restaurantList() {
        if(!$this->variables['isAdmin']) {
            $this->redirect->to('/');
        }
        $this->variables['restaurants'] = $this->restaurantRepository->getRestaurants(false);
        $this->render('restaurantListPanel', $this->variables);
    }
}