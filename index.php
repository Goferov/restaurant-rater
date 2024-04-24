<?php

spl_autoload_register(function(string  $classNamespace) {
    $classNamespace = str_replace(['\\','App/'],['/',''],$classNamespace);
    $path = 'src/' . $classNamespace . '.php';
    require_once $path;
});


use App\Router;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

Router::get('', 'HomepageController');
Router::get('panel', 'PanelController');
Router::get('restaurant', 'RestaurantController');
Router::get('contact', 'ContactController');
Router::post('addRestaurant', 'RestaurantController');
Router::post('saveRestaurant', 'RestaurantController');
Router::post('saveReview', 'RestaurantController');
Router::post('search', 'RestaurantController');

Router::post('login', 'UserController');
Router::post('register', 'UserController');
Router::get('logout', 'UserController');
Router::post('changePassword', 'UserController');

Router::run($path);