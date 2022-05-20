<?php

namespace Chat;

class ChatHandler
{
    private $twig;

    /**
     * @param $twig
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
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