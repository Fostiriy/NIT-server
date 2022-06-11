<?php

declare(strict_types=1);

namespace Domain\Entity;

abstract class ActiveRecord
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    // Получение всех записей
    abstract public function getAll(): array;

    // Получение записи по ID
    abstract public function getByID(int $id): object;

    // Получение записей по значению поля из таблицы (фильтрация по полю)
    abstract public function getByFieldValue(string $fieldName, $fieldValue): array;

    // Сохранение записи
    abstract public function save(): bool;

    // Удаление записи
    abstract public function remove(): bool;

    abstract public function findID(): int;
}