<?php

namespace Domain\Repository\DataMapper;

use Domain\Entity\Dog;

class DogDataMapper
{
    private const MAP = [
        "name" => [
            "type" => "integer",
            "setter" => "setClichka",
            "nullable" => false,
            "default" => "Бобик"
        ]
    ];

    public function map(array $row): ?Dog
    {
        if (isset($row["name"]) && empty($row["name"])) {
            return null;
        }

        return new Dog($row["name"], $row["weight"]);
    }
}