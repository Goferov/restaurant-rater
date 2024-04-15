<?php

namespace App;
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

        if (!array_key_exists($action, self::$routes))
            die("Wrong url!");

        $controller = 'App\Controllers\\' . self::$routes[$action];
        $object = new $controller;
        $action = $action ?: 'index';
        $id = $urlParts[1] ?? '';

        $object->$action($id);
    }
}