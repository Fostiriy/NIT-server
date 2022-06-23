<?php

use Controller\AuthController;
use Controller\ChatController;
use Controller\UserController;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$loader = new FilesystemLoader(dirname(__DIR__) . "/templates/");
$twig = new Environment($loader);
$authController = new AuthController($twig);
$userController = new UserController();

if (!$userController->check()) {
    $authController->showAuthPage();
} else {
    $authController->showUserMessagesPage($userController->getLogin());
    $chatController = new ChatController($twig);
    $chatController->print_messages($userController->getLogin());
}