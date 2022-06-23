<?php

use Controller\AuthController;
use Controller\ChatController;
use Controller\UserController;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$loader = new FilesystemLoader(dirname(__DIR__) . "/templates/");
$twig = new Environment($loader);

$log = new Logger('login');
$user_handler = new StreamHandler('chat.log', Logger::INFO);
$log->pushHandler($user_handler);

$chatController = new ChatController($twig);
$userController = new UserController();
$userName = $userController->getLogin();

$authController = new AuthController($twig);
$authController->showUserMessagesPage($userName);

$chatController->print_messages($userName);
$chatController->add_message($userName);