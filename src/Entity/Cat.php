<?php

namespace Domain\Entity;

class Cat extends \Domain\Entity\ActiveRecord
{
    private const TABLE = "cat";

    private $name;
    private $breed;
    private $color;

    public static function create(string $name, string $breed, string $color)
    {
        $cat = new Cat();
        $cat->breed = $breed;
        $cat->color = $color;
        $cat->name = $name;
    }

    function save(): void
    {
        $sql = "INSERT INTO " . self::TABLE . "(name, breed, color) VALUE (:name, :breed, :color)";
        $query = $this->getConnection()->prepare($sql);
        $query->bindParam();
        try {
            $query->execute();
        } catch (\Throwable $exception) {

        }
    }

    function remove(): void
    {
        // TODO: Implement remove() method.
    }

    function getByID(int $id)
    {
        // TODO: Implement getByID() method.
    }

    function all(): array
    {
        return "";
    }

    function getByField(array $fieldsValue)
    {
        // TODO: Implement getByField() method.
    }
}