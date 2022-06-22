<?php

namespace Controller;

use Twig\Environment;

class AuthController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invokeLogMenu()
    {
        $this->twig->display("web/auth.html.twig");
    }

    public function __invokeShowTable($result, $name)
    {
        $this->twig->display("web/user-messages.html.twig", ['t' => $result, 'name' => $name]);
    }
}