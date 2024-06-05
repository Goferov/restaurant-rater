<?php

spl_autoload_register(function(string  $classNamespace) {
    $classNamespace = str_replace(['\\','App/'],['/',''],$classNamespace);
    $path = 'src/' . $classNamespace . '.php';
    require_once $path;
});


use App\Router;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

$container = require_once 'src/bootstrap.php';
$router = new Router($container);

$router->get('', 'HomepageController');
$router->get('panel', 'PanelController');
$router->get('restaurantList', 'PanelController');
$router->get('restaurant', 'RestaurantController');
$router->get('contact', 'ContactController');
$router->post('addRestaurant', 'RestaurantController');
$router->post('saveRestaurant', 'RestaurantController');
$router->post('saveReview', 'RestaurantController');
$router->post('search', 'RestaurantController');
$router->post('deleteRestaurant', 'RestaurantController');
$router->post('publicateRestaurant', 'RestaurantController');
$router->post('error404', 'ErrorController');

$router->post('login', 'UserController');
$router->post('register', 'UserController');
$router->get('logout', 'UserController');
$router->post('changePassword', 'UserController');

$router->run($path);