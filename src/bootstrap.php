<?php
namespace App;

use App\Helpers\IReviewHelper;
use App\Helpers\ReviewHelper;
use App\Repository\IRestaurantRepository;
use App\Repository\IReviewRepository;
use App\Repository\IUserRepository;
use App\Repository\RestaurantRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Services\FileService;
use App\Services\IFileService;
use App\Services\IMessageService;
use App\Services\IValidatorService;
use App\Services\MessageService;
use App\Services\ValidatorService;
use App\Utils\Auth;
use App\Utils\Redirect;
use App\Utils\Request;
use App\Utils\Session;
use App\Utils\Validators\EmailValidator;
use App\Utils\Validators\IValidator;
use App\Utils\Validators\IValidatorManager;
use App\Utils\Validators\PasswordValidator;
use App\Utils\Validators\PhoneNumberValidator;
use App\Utils\Validators\PostalCodeValidator;
use App\Utils\Validators\RequiredFieldsValidator;
use App\Utils\Validators\UrlValidator;
use App\Utils\Validators\ValidatorManager;

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
$container->set(IRestaurantRepository::class, function ($container) {
    return new RestaurantRepository($container->get(Database::class));
});

$container->set(IReviewRepository::class, function ($container) {
    return new ReviewRepository($container->get(Database::class));
});

$container->set(IUserRepository::class, function ($container) {
    return new UserRepository($container->get(Database::class));
});

$container->set(Request::class, function() {
    return new Request($_GET, $_POST, $_SERVER, $_FILES);
});

$container->set(Session::class, function() {
    return new Session();
});

$container->set(IReviewHelper::class, function() {
    return new ReviewHelper();
});

$container->set(IValidator::class, function() {
    return new PasswordValidator();
});

$container->set(IValidator::class, function() {
    return new PasswordValidator();
});

$container->set(IFileService::class, function() {
    return new FileService();
});

$container->set(Auth::class, function($container) {
    return new Auth($container->get(Session::class));
});

$container->set(Redirect::class, function($container) {
    return new Redirect($container->get(Request::class));
});

$container->set(IValidatorManager::class, function() {
    $validatorManager = new ValidatorManager();
    $validatorManager->addValidator('email', new EmailValidator());
    $validatorManager->addValidator('url', new UrlValidator());
    $validatorManager->addValidator('postalCode', new PostalCodeValidator());
    $validatorManager->addValidator('phoneNumber', new PhoneNumberValidator());
    $validatorManager->addValidator('requiredFields', new RequiredFieldsValidator());
    return $validatorManager;
});

$container->set(IMessageService::class, function() {
    return new MessageService();
});

$container->set(IValidatorService::class, function($container) {
    return new ValidatorService($container->get(IValidatorManager::class), $container->get(IMessageService::class));
});

return $container;