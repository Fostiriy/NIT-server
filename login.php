<?php


use App\Controller\userController;

require_once __DIR__ . "/vendor/autoload.php";

$login = $_POST['login'];
$password = $_POST['password'];

if ($login != '' && $password != '') {
    $userController = new userController();
    $userController->login($login, $password);
}

header("LOCATION: " . 'http' . '://' . $_SERVER['HTTP_HOST'] . '/');