<?php

namespace Controller;

use Model\Entity\Message;
use Model\Entity\User;
use Model\Repository\MessageRepository;
use Model\Repository\UserRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use PDOException;

class ChatController
{
    private $twig;
    private $log;
    private $chat_handler;
    private MessageRepository $messageRepository;
    private UserRepository $userRepository;

    /**
     * @param $twig
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
        $this->log = new Logger('chat');
        $this->chat_handler = new StreamHandler('chat.log', Logger::INFO);

        $host = "localhost";
        $dbname = "chat";
        $user_name = "fostiriy";
        $pass = "RTrtV0h$";
        $DBH = new PDO("mysql:host=$host;port=3306;dbname=$dbname", $user_name, $pass);
        $DBH->exec("USE chat");
        $this->messageRepository = new MessageRepository($DBH);
        $this->userRepository = new UserRepository($DBH);
    }

    public function print_messages($user_name)
    {
        $messages = $this->messageRepository->getAll();
        $author_id = 0;
        if ($user_name === "admin") {
            $users = $this->userRepository->getAll();
            echo '<pre>';
            print_r($users);
            echo '</pre>';
        } else {
            $author_id = $this->userRepository->findID(new User($user_name));
        }

        foreach ($messages as $message) {
            if ($author_id == 0 || $author_id == $message->getAuthorId()) {
                $this->twig->display("web/message.html.twig", [
                    "message" => [
                        "date" => $message->getMessageDate(),
                        "user" => $this->userRepository->getByID($message->getAuthorId())->getUserName(),
                        "message" => $message->getMessageText(),
                    ],
                ]);
            }
        }
    }

    public function add_message($user_name)
    {
        $messageText = empty($_POST["message"]) ? "" : $_POST["message"];

        // adding message
        if (isset($messageText) && $messageText !== "") {
            $this->messageRepository->save(new Message($this->userRepository->findID(new User($user_name)), $messageText));

            $this->log->pushHandler($this->chat_handler);
            $this->log->info("New message", ["username" => $user_name]);
        }

        $this->twig->display("web/message.html.twig", [
            "message" => [
                "date" => date('Y-m-d H:i:s', time()),
                "user" => $user_name,
                "message" => $messageText,
            ],
        ]);
    }

    public function is_user_exists($user_name): bool
    {
        $result = false;

        try {
            $author_id = $this->userRepository->getByFieldValue("user_name", $user_name)[0]["user_id"];
            $result = !empty($author_id);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
        }

        return $result;
    }

    public function get_password($user_name): string
    {
        return $this->userRepository->getByFieldValue("user_name", $user_name)[0]["password"];
    }
}