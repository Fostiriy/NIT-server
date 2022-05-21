<?php

declare(strict_types=1);

namespace Domain\Entity;

abstract class ActiveRecord
{
    private $connection;

    public function __constuct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    abstract function save(): void;

    abstract function remove(): void;

    abstract function getByID(int $id);

    abstract function all(): array;

    abstract function getByField(array $fieldsValue);

}