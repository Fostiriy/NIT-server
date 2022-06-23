<?php

namespace Model\Repository;

use Model\Entity\User;
use Model\Mapper\UserMapper;

class UserRepository
{
    private const TABLE = "user";
    private $connection;
    private $dataMapper;

    /**
     * @param $connection
     * @param $dataMapper
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->dataMapper = new UserMapper();
    }

    // Получение всех записей
    public function getAll(): array
    {
        $result = [];

        $sql = "SELECT * FROM " . self::TABLE;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $result[] = $this->dataMapper->map($row);
        }

        return $result;
    }

    // Получение записи по ID
    public function getByID(int $id): ?User
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $this->dataMapper->map($row);
    }

    // Получение записей по значению поля из таблицы (фильтрация по полю)
    public function getByFieldValue(string $fieldName, $fieldValue): array
    {
        $result = [];

        $sql = "SELECT * FROM " . self::TABLE . " WHERE " . $fieldName . " = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$fieldValue]);

        while ($row = $stmt->fetch()) {
            $result[] = $this->dataMapper->map($row);
        }

        return $result;
    }

    // Сохранение записи
    public function save(User $user): bool
    {
        $sql = "INSERT INTO " . self::TABLE . "(user_name, password) VALUE (?, ?)";
        $query = $this->connection->prepare($sql);
        $query->execute([$user->getUserName(), $user->getPassword()]);

        return $query->rowCount() > 0;
    }

    // Удаление записи
    public function remove(User $user): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$this->findID($user)]);

        return $stmt->rowCount() > 0;
    }

    // Поиск ID записи
    public function findID(User $user): int
    {
        $sql = "SELECT user_id FROM " . self::TABLE . " WHERE user_name = ?";
        $query = $this->connection->prepare($sql);
        $query->execute([$user->getUserName()]);

        return $query->fetchColumn();
    }
}