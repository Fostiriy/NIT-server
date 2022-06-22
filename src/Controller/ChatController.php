<?php

namespace Controller;

use Model\Entity\Message;
use Model\Entity\User;
use Model\Repository\MessageRepository;
use Model\Repository\UserRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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
     * @param $user
     * @param $messageRepository
     * @param $userRepository
     */
    public function __construct($twig, $messageRepository, $userRepository)
    {
        $this->twig = $twig;
        $this->log = new Logger('chat');
        $this->chat_handler = new StreamHandler('chat.log', Logger::INFO);
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
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
        $messageText = empty($_GET["message"]) ? "" : $_GET["message"];

        // adding message
        if (isset($messageText) && $messageText !== "") {
            $this->messageRepository->save(new Message($this->userRepository->findID(new User($user_name)), $messageText));

            $this->log->pushHandler($this->chat_handler);
            $this->log->info("New message", ["username" => $user_name]);
        }
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