<?php

namespace Domain\Chat;

use Domain\Entity\User;
use Domain\Repository\MessageRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use PDOException;

class ChatHandler
{
    private $twig;
    private $log;
    private $chat_handler;
    private $DBH;
    private User $user;
    private MessageRepository $messageRepository;

    /**
     * @param $twig
     * @param $DBH
     * @param $user
     * @param $messageRepository
     */
    public function __construct($twig, $DBH, $user, $messageRepository)
    {
        $this->twig = $twig;
        $this->log = new Logger('chat');
        $this->chat_handler = new StreamHandler('chat.log', Logger::INFO);
        $this->DBH = $DBH;
        $this->user = $user;
        $this->messageRepository = $messageRepository;
    }

    public function print_messages($user_name)
    {
        try {
            $messages = $this->messageRepository->getAll();
            $author_id = 0;
            if ($user_name === "admin") {
                $users = $this->user->getAll();
                echo '<pre>';
                print_r($users);
                echo '</pre>';
            } else {
                $this->user->setUserName($user_name);
                $author_id = $this->user->findID();
            }

            foreach ($messages as $message) {
                if ($author_id == 0 || $author_id == $message->getAuthorId()) {
                    $this->twig->display("web/message.html.twig", [
                        "message" => [
                            "date" => $message->getMessageDate(),
                            "user" => $this->user->getByID($message->getAuthorId())->getUserName(),
                            "message" => $message->getMessageText(),
                        ],
                    ]);
                }
            }
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
        }
    }

    public function add_message($user)
    {
        $message = empty($_GET["message"]) ? "" : $_GET["message"];

        // adding message
        if (isset($message) && $message !== "") {
            try {
                $query = $this->DBH->prepare("SELECT user_id FROM user WHERE user_name = ?");
                $query->execute([$user]);
                $author_id = $query->fetchColumn();
                $query = $this->DBH->prepare("INSERT INTO chat(message_date, author_id, message_text) VALUE (?, ?, ?)");
                $query->execute([
                    date('Y-m-d H:i:s', time()), $author_id, $message,
                ]);
            } catch (PDOException $e) {
                echo "Error!: " . $e->getMessage() . "<br/>";
            }

            $this->log->pushHandler($this->chat_handler);
            $this->log->info("New message", ["username" => $user]);
        }
    }

    public function is_user_exists($user_name): bool
    {
        $result = false;

        try {
            $author_id = $this->user->getByFieldValue("user_name", $user_name)[0]["user_id"];
            $result = !empty($author_id);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
        }

        return $result;
    }

    public function get_password($user_name): string
    {
        $result = "";

        try {
            $query = $this->DBH->prepare("SELECT password FROM user WHERE user_name = ?");
            $query->execute([$user_name]);
            $result = $query->fetchColumn();
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
        }

        return $result;
    }
}