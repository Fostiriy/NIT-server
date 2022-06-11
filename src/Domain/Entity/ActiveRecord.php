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

    // Получение всех записей
    abstract public function getAll(): array;

    // Получение записи по ID
    abstract public function getByID(int $id): object;

    // Получение записей по значению поля из таблицы (фильтрация по полю)
    abstract public function getByFieldValues(string $fieldName, array $fieldValues): array;

    // Сохранение записи
    abstract public function save(): void;

    // Удаление записи
    abstract public function remove(): void;
}