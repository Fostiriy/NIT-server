<?php

namespace Application\Web;

use Domain\DTO\InfoDTO;
use Twig\Environment;

class AboutMyselfController
{
    private $twig;

    /**
     * @param $twig
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @param Environment $twig
     */
    public function setTwig($twig)
    {
        $this->twig = $twig;
    }

    public function __invoke()
    {
        $info = new InfoDTO();
        $info->setName("Alexey");
        $info->setSurname("Burakov");
        $info->setAge(20);

        $contacts = [
            "steam" => "fostiriy",
            "vk" => "alexeybur",
            "phone" => "89619502118",
        ];

        return $this->twig->render("web/about-myself.html.twig", [
            "title" => "Page",
            "info" => $info,
            "contacts" => $contacts,
        ]);
    }
}