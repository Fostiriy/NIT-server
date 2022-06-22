<?php

use Controller\ChatController;
use Model\Entity\User;
use Model\Mapper\MessageMapper;
use Model\Mapper\UserMapper;
use Model\Repository\MessageRepository;
use Model\Repository\UserRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once dirname(__DIR__) . "/vendor/autoload.php";

try {
    $host = "localhost";
    $dbname = "chat";
    $user_name = "fostiriy";
    $pass = "RTrtV0h$";
    $DBH = new PDO("mysql:host=$host;port=3306;dbname=$dbname", $user_name, $pass);
    $DBH->exec("USE chat");
} catch (PDOException $e) {
    echo $e->getMessage();
}

$loader = new FilesystemLoader(dirname(__DIR__) . "/templates/");
$twig = new Environment($loader);

$log = new Logger('login');
$user_handler = new StreamHandler('chat.log', Logger::INFO);
$log->pushHandler($user_handler);

$messageDataMapper = new MessageMapper();
$userDataMapper = new UserMapper();
$messageRepository = new MessageRepository($DBH, $messageDataMapper);
$userRepository = new UserRepository($DBH, $userDataMapper);
$chat = new ChatController($twig, $messageRepository, $userRepository);

$twig->display("web/chat.html.twig");

$user_name = empty($_GET["user"]) ? "" : $_GET["user"];
$password = empty($_GET["password"]) ? "" : $_GET["password"];

if (isset($user_name) && $user_name != "") {
    if (isset($password) && $password != "") {
        // adding user
        if (!$chat->is_user_exists($user_name)) {
            try {
                if ($userRepository->save(new User($user_name, $password))) {
                    echo "<p><i>Создан пользователь <b>$user_name</b></i></p>";
                    $log->info("Adding a new user", ["username" => $user_name]);

                    $chat->add_message($user_name);
                    $chat->print_messages($user_name);
                } else {
                    echo "<p><i>Недопустимые данные. Попробуйте изменить имя пользователя и пароль.</i></p>";
                }
            } catch (PDOException $e) {
                echo "Error!: " . $e->getMessage() . "<br/>";
            }

        } else { // checking password
            $proper_password = $chat->get_password($user_name);

            if ($password == $proper_password) {
                $log->info("User signed in", ["username" => $user_name]);
                $chat->add_message($user_name);
                $chat->print_messages($user_name);
            } else {
                $log->error("Wrong password", ["username" => $user_name]);
                echo "<p style='color: darkred'><i>Неверный пароль</i></p>";
            }
        }
    } else {
        echo "<p style='color: darkred'><i>Введите пароль</i></p>";
    }
}