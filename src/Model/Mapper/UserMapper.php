<?php

namespace Model\Mapper;

use Model\Entity\User;

class UserMapper
{
    private const MAP = [
        "user_name" => [
            "type" => "integer",
            "nullable" => false,
        ],
        "password" => [
            "type" => "string",
            "nullable" => false,
        ],
    ];

    public function map(array $row): ?User
    {
        $result = null;

        if (!(isset($row["user_name"]) && empty($row["user_name"])
            || isset($row["password"]) && empty($row["password"]))) {
            $result = new User($row["user_name"], $row["password"]);
        }

        return $result;
    }

}