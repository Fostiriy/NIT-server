<?php

use Controller\UserController;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$userController = new UserController();
$userController->logout();

header("LOCATION: " . 'http' . '://' . $_SERVER['HTTP_HOST'] . '/');