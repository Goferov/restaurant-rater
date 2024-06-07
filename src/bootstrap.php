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
use App\Services\FileService;
use App\Utils\Auth;
use App\Utils\Redirect;
use App\Validators\EmailValidator;
use App\Validators\IValidator;
use App\Validators\IValidatorManager;
use App\Validators\PasswordValidator;
use App\Validators\PhoneNumberValidator;
use App\Validators\PostalCodeValidator;
use App\Validators\RequiredFieldsValidator;
use App\Validators\UrlValidator;
use App\Validators\ValidatorManager;

$container = new Container();
$container->set(Database::class, function() {
    $dbConfig = Config::get('db');

    return new Database(
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['host'],
        $dbConfig['database'],
        $dbConfig['port']
    );
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

$container->set(FileService::class, function() {
    return new FileService();
});

$container->set(Auth::class, function($container) {
    return new Auth($container->get(Session::class));
});

$container->set(Redirect::class, function($container) {
    return new Redirect($container->get(Request::class));
});

$container->set(IValidatorManager::class, function($container) {
    $validatorManager = new ValidatorManager();
    $validatorManager->addValidator('email', new EmailValidator());
    $validatorManager->addValidator('url', new UrlValidator());
    $validatorManager->addValidator('postalCode', new PostalCodeValidator());
    $validatorManager->addValidator('phoneNumber', new PhoneNumberValidator());
    $validatorManager->addValidator('RequiredFields', new RequiredFieldsValidator());
    return $validatorManager;
});


return $container;