<?php
namespace App\Controllers;

use App\Config;
use App\Repository\RestaurantRepositoryI;
use App\Request;
use App\Session;
use App\Utils\Auth;
use App\Utils\Redirect;

class PanelController extends AppController {

    private ?array $userSession;
    private array $variables;
    private Session $session;
    private Request $request;
    private RestaurantRepositoryI $restaurantRepository;
    private Auth $auth;
    private Redirect $redirect;

    public function  __construct(Session $session, Request $request, RestaurantRepositoryI $restaurantRepository, Auth $auth, Redirect $redirect) {
        $this->session = $session;
        $this->request = $request;
        $this->restaurantRepository = $restaurantRepository;
        $this->auth = $auth;
        $this->redirect = $redirect;
        $this->userSession = $this->session->get('userSession');
        $this->variables['isAdmin'] = $this->auth->isAdminUser();
    }

    public function panel() {
        if($this->userSession) {
            $messagesList = Config::get('messages');
            $messageKey = $this->request->get('message');
            $this->variables['message'] = $messagesList[$messageKey] ?? null;
            $this->redirect->to('panel', $this->variables);
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