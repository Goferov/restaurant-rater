<?php

spl_autoload_register(function(string  $classNamespace) {
    $classNamespace = str_replace(['\\','App/'],['/',''],$classNamespace);
    $path = 'src/' . $classNamespace . '.php';
    require_once $path;
});


use App\Controllers\AppController;

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url( $path, PHP_URL_PATH);
$action = explode("/", $path)[0];
$action = $action == null ? 'login': $action;

(new AppController())->render($action);