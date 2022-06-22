<?php

use App\Controller\baseController;
use App\Controller\userController;
use App\Model\Entity\Article;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

require_once __DIR__ . "/vendor/autoload.php";

$loader = new FilesystemLoader(__DIR__ . '/templates/');
$twig = new Environment($loader);
$baseController = new baseController($twig);
$userController = new userController();

if (!$userController->check()) {
    $baseController->__invokeLogMenu();
} else {
    $article = new Article();
    $baseController->__invokeShowTable($article->getAll(), $userController->getLogin());
}