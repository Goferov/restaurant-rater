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
use App\Validators\EmailValidator;
use App\Validators\IValidatorManager;
use App\Validators\PasswordValidator;
use App\Validators\IValidator;
use App\Validators\PhoneNumberValidator;
use App\Validators\PostalCodeValidator;
use App\Validators\UrlValidator;
use App\Validators\ValidatorManager;

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

$container->set(IValidator::class, function() {
    return new PasswordValidator();
});

$container->set(IValidatorManager::class, function() {
    $validatorManager = new ValidatorManager();
    $validatorManager->addValidator('email', new EmailValidator());
    $validatorManager->addValidator('url', new UrlValidator());
    $validatorManager->addValidator('postalCode', new PostalCodeValidator());
    $validatorManager->addValidator('phoneNumber', new PhoneNumberValidator());
    return $validatorManager;
});


return $container;