<?php

use Application\Web\AboutMyselfController;
use Application\Web\UserDataController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$loader = new FilesystemLoader(dirname(__DIR__) . "/templates/");
$twig = new Environment($loader);

if (preg_match('/\/reg/', $_SERVER['REQUEST_URI'])) {
    $controller = new UserDataController();
    $controller->reg($_GET['log'], $_GET['pass']);
} else {
    $controller = new AboutMyselfController($twig);
    echo $controller();
}
