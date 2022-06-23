<?php

namespace Controller;

use Twig\Environment;
use Controller\ChatController;

class AuthController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function showAuthPage()
    {
        $this->twig->display("web/auth.html.twig");
    }

    public function showUserMessagesPage($userName)
    {
        $this->twig->display("web/user-messages.html.twig", ["user_name" => $userName]);
        $chatController = new ChatController($twig);
    }
}