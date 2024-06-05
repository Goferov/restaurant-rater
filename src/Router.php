<?php

namespace App;

class Router
{
    private Container $container;
    private array $routes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $url, string $controller): void
    {
        $this->routes['GET'][$url] = $controller;
    }

    public function post(string $url, string $controller): void
    {
        $this->routes['POST'][$url] = $controller;
    }

    public function run(string $path): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $urlParts = explode("/", $path);
        $action = $urlParts[0] ?? '';

        if (!isset($this->routes[$method][$action])) {
            http_response_code(404);
            header('Location: /error404');
            exit;
        }

        $controllerClass = 'App\Controllers\\' . $this->routes[$method][$action];
        $controller = $this->container->build($controllerClass);
        $actionMethod = $action ?: 'index';
        $id = $urlParts[1] ?? '';

        if (!method_exists($controller, $actionMethod)) {
            http_response_code(404);
            header('Location: /error404');
            exit;
        }

        $controller->$actionMethod($id);
    }
}