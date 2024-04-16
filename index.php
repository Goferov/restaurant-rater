<?php

spl_autoload_register(function(string  $classNamespace) {
    $classNamespace = str_replace(['\\','App/'],['/',''],$classNamespace);
    $path = 'src/' . $classNamespace . '.php';
    require_once $path;
});


use App\Router;

$path = trim($_SERVER['REQUEST_URI'], '/');

Router::get('', 'HomepageController');
Router::get('panel', 'PanelController');
Router::post('login', 'UserController');
Router::post('register', 'UserController');

Router::run($path);