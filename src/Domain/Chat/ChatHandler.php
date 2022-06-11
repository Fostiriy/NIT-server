<?php

namespace Domain\Chat;

use Domain\Entity\User;
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

    /**
     * @param $twig
     * @param $DBH
     * @param $user
     */
    public function __construct($twig, $DBH, $user)
    {
        $this->twig = $twig;
        $this->log = new Logger('chat');
        $this->chat_handler = new StreamHandler('chat.log', Logger::INFO);
        $this->DBH = $DBH;
        $this->user = $user;
    }

    public function print_messages($user_name)
    {
        try {
            if ($user_name === "admin") {
                $query = $this->DBH->prepare("SELECT message_date, message_text, 
       (SELECT user_name FROM user WHERE user_id = author_id) user_name FROM chat");
                $query->execute();
            } else {
                $this->user->setUserName($user_name);
                $author_id = $this->user->findID();

                $query = $this->DBH->prepare("SELECT message_date, message_text, ? user_name FROM chat WHERE author_id = ?");
                $query->execute([$user_name, $author_id]);
            }

            while ($row = $query->fetch(PDO::FETCH_LAZY)) {
                $this->twig->display("web/message.html.twig", [
                    "message" => [
                        "date" => $row->message_date,
                        "user" => $row->user_name,
                        "message" => $row->message_text,
                    ],
                ]);
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