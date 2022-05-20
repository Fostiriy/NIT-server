<?php

namespace Chat;

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

    /**
     * @param $twig
     * @param $DBH
     */
    public function __construct($twig, $DBH)
    {
        $this->twig = $twig;
        $this->log = new Logger('chat');
        $this->chat_handler = new StreamHandler('chat.log', Logger::INFO);
        $this->DBH = $DBH;
    }

    public function print_messages($user)
    {
        $users_json = json_decode(file_get_contents("users.json"), true);
        $messages = $users_json["messages"];

        foreach ($messages as $message) {
            if ($message["user"] == $user || $user == "default") {
                $this->twig->display("web/message.html.twig", ["message" => $message,]);
            }
        }
    }

    public function add_message($user)
    {
        $message = empty($_GET["message"]) ? "" : $_GET["message"];
        $users_json = json_decode(file_get_contents("users.json"), true);

        // adding message
        if (isset($message) && $message !== "") {
            $users_json["messages"][] = [
                "date" => date('m-d-Y H:i', time()),
                "user" => $user,
                "message" => $message
            ];
            file_put_contents("users.json", json_encode($users_json));
            try {
                $query = $this->DBH->prepare("SELECT user_id FROM user WHERE user_name = ?");
                $query->execute([$user]);
                $author_id = $query->fetchColumn();
                $query = $this->DBH->prepare("INSERT INTO chat(message_date, author_id, message_text) VALUE (?, ?, ?)");
                $query->execute([
                    date('Y-m-d H:i', time()), $author_id, $message,
                ]);
            } catch (PDOException $e) {
                echo "Error!: " . $e->getMessage() . "<br/>";
            }

            $this->log->pushHandler($this->chat_handler);
            $this->log->info("New message", ["username" => $user]);
        }
    }

    public function is_user_exists($user): bool
    {
        $result = false;

        $users_json = json_decode(file_get_contents("users.json"), true);
        foreach ($users_json["users"] as $item) {
            if ($item["user"] == $user) {
                $result = true;
            }
        }

        return $result;
    }

    public function get_password($user)
    {
        $result = "";

        $users_json = json_decode(file_get_contents("users.json"), true);
        foreach ($users_json["users"] as $item) {
            if ($item["user"] == $user) {
                $result = $item["password"];
            }
        }

        return $result;
    }
}