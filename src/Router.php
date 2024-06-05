<?php

namespace App;
use App\Controllers\AppController;
use App\Controllers\RestaurantController;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantRepositoryI;
use App\Repository\ReviewRepository;
use App\Repository\ReviewRepositoryI;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryI;

class Router
{
    public static array $routes;

    public static function get($url, $view): void
    {
        self::$routes[$url] = $view;
    }

    public static function post($url, $view): void
    {
        self::$routes[$url] = $view;
    }

    static public function run(string $path): void
    {
        $urlParts = explode("/", $path);
        $action = $urlParts[0];

        $container = new Container();

        $container->set(RestaurantRepositoryI::class, function ($container) {
            return new RestaurantRepository();
        });
        $container->set(ReviewRepositoryI::class, function ($container) {
            return new ReviewRepository();
        });
        $container->set(UserRepositoryI::class, function ($container) {
            return new UserRepository();
        });



        if (!array_key_exists($action, self::$routes)) {
            http_response_code(401);
            header('Location: /error404');
            exit;
        }

        $controller = 'App\Controllers\\' . self::$routes[$action];
//        $object = new $controller;
        $object = $container->build($controller);
        $action = $action ?: 'index';
        $id = $urlParts[1] ?? '';

        $object->$action($id);
    }
}