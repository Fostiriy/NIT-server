<?php

use Chat\ChatHandler;
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
$twig->display("web/chat.html.twig");
$chat = new ChatHandler($twig, $log);

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
        $log->info("Adding a new user", ["username" => $user]);
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
            $log->info("User signed in", ["username" => $user]);
            $chat->add_message($user);
            $chat->print_messages($user);
        } else {
            $log->error("Wrong password", ["username" => $user]);
            echo "<p style='color: darkred'><i>Неверный пароль</i></p>";
        }
    }
} else {
    echo "<p style='color: darkred'><i>Введите пароль</i></p>";
}