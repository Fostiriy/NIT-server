<?php


use App\Controller\userController;

require_once __DIR__ . "/vendor/autoload.php";

$userController = new userController();
$userController->logout();

header("LOCATION: " . 'http' . '://' . $_SERVER['HTTP_HOST'] . '/');