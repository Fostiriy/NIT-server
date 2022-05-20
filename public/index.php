<?php

use Application\Web\AboutMyselfController;
use Chat\ChatHandler;
use Chat\PageBuilder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$loader = new FilesystemLoader(dirname(__DIR__) . "/templates/");
$twig = new Environment($loader);
$log = new Monolog\Logger('name');
$log->pushHandler(new Monolog\Handler\StreamHandler('app.log', Monolog\Logger::WARNING));
$log->warning('Foo');

$pageBuilder = new PageBuilder();
$chat = new ChatHandler();

$pageBuilder->buildChatPage();

$user = empty($_GET["user"]) ? "" : $_GET["user"];
$password = empty($_GET["password"]) ? "" : $_GET["password"];

if (!isset($user) || $user == "" || $user == "default") {
    $chat->add_message("default");
    $chat->print_messages("default");
} elseif (isset($password) && $password != "") {
    $users_json = json_decode(file_get_contents("users.json"), true);

    // adding user
    if (!$chat->is_user_exists($user)) {
        echo "<p><i>Создан пользователь <b>$user</b></i></p>";
        $users_json["users"][] = [
            "user" => $user,
            "password" => $password
        ];
        file_put_contents("users.json", json_encode($users_json));
        $chat->add_message($user);
        $chat->print_messages($user);
    } else { // checking password
        $proper_password = $chat->get_password($user);

        if ($password == $proper_password) {
            $chat->add_message($user);
            $chat->print_messages($user);
        } else {
            echo "<p style='color: darkred'><i>Неверный пароль</i></p>";
        }
    }
} else {
    echo "<p style='color: darkred'><i>Введите пароль</i></p>";
}