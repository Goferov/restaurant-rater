<?php
namespace App;

use App\Helpers\ReviewHelper;
use App\Helpers\ReviewHelperI;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantRepositoryI;
use App\Repository\ReviewRepository;
use App\Repository\ReviewRepositoryI;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryI;

$container = new Container();
$container->set(Database::class, function() {
    return new Database(Config::get('db'));
});
$container->set(RestaurantRepositoryI::class, function ($container) {
    return new RestaurantRepository($container->get(Database::class));
});

$container->set(ReviewRepositoryI::class, function ($container) {
    return new ReviewRepository($container->get(Database::class));
});

$container->set(UserRepositoryI::class, function ($container) {
    return new UserRepository($container->get(Database::class));
});

$container->set(Request::class, function() {
    return new Request($_GET, $_POST, $_SERVER, $_FILES);
});

$container->set(Session::class, function() {
    return new Session();
});

$container->set(ReviewHelperI::class, function() {
    return new ReviewHelper();
});


return $container;