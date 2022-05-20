<?php

spl_autoload_register(function ($class_name) {
    $dir = dirname(__DIR__) . "/src/";
    $extension = ".php";
    $path = $dir . str_replace("\\", "/", $class_name) . $extension;
    require_once $path;
});

use Chat\ChatHandler;
use Chat\PageBuilder;

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