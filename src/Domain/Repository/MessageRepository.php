<?php

namespace Domain\Repository;

use Domain\Entity\Message;
use PDO;

class MessageRepository
{
    private const TABLE = "chat";
    private $connection;
    private $dataMapper;

    /**
     * @param $connection
     * @param $dataMapper
     */
    public function __construct($connection, $dataMapper)
    {
        $this->connection = $connection;
        $this->dataMapper = $dataMapper;
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

    // Получение записи по id
    public function getByID(int $id): ?Message
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE message_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $this->dataMapper->map($row);
    }

    // Получение записей по значению поля из таблицы (фильтрация по полю)
    public function getByFieldValue(string $fieldName, $fieldValue): array
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE " . $fieldName . " = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$fieldValue]);

        return $stmt->fetchAll();
    }

    // Сохранение записи
    public function save(Message $message): bool
    {
        $sql = "INSERT INTO " . self::TABLE . "(message_date, author_id, message_text) VALUE (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($message->getInfo());

        return $stmt->rowCount() > 0;
    }

    // Удаление записи
    public function remove(Message $message): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE message_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$this->findID($message)]);

        return $stmt->rowCount() > 0;
    }

    // Поиск ID записи
    public function findID(Message $message): int
    {
        $sql = "SELECT message_id FROM " . self::TABLE . " WHERE message_date = ? AND author_id = ? AND message_text = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($message->getInfo());

        return $stmt->fetchColumn();
    }

}