<?php

use Controller\UserController;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$login = $_POST['login'];
$password = $_POST['password'];

if ($login != '' && $password != '') {
    $userController = new UserController();
    $userController->register($login, $password);
}

header("LOCATION: " . 'http' . '://' . $_SERVER['HTTP_HOST'] . '/');