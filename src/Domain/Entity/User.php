<?php

namespace Domain\Entity;

class User extends ActiveRecord
{
    private const TABLE = "user";

    private string $user_name;
    private string $password;

    /**
     * @param string $user_name
     * @param string $password
     */
    public function __construct(\PDO $connection, string $user_name = "", string $password = "")
    {
        parent::__construct($connection);
        $this->user_name = $user_name;
        $this->password = $password;
    }

    public function createNewUser(string $user_name, string $password): User
    {
        return new User($this->getConnection(), $user_name, $password);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM " . self::TABLE;
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute();
        } catch (\Throwable $exception) {

        }

        return $query->fetchAll();
    }

    public function getByID(int $id): object
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE user_id = :id";
        $query = $this->getConnection()->prepare($sql);
        $query->bindParam("id", $id);
        try {
            $query->execute();
        } catch (\Throwable $exception) {

        }
        $result = $query->fetch(PDO::FETCH_LAZY);

        return self::createNewUser($result->user_id, $result->user_name, $result->password);
    }

    public function getByFieldValues(string $fieldName, $fieldValues): array
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE ? IN(?)";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$fieldName, $fieldValues]);
        } catch (\Throwable $exception) {

        }

        return $query->fetchAll();
    }

    public function save(): void
    {
        $sql = "INSERT INTO " . self::TABLE . "(user_name, password) VALUE (?, ?)";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->user_name, $this->password]);
        } catch (\Throwable $exception) {

        }
    }

    public function remove(): void
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE user_id = ?";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->getID()]);
        } catch (\Throwable $exception) {

        }
    }

    public function getID(): int
    {
        $sql = "SELECT user_id FROM " . self::TABLE . " WHERE user_name = ? AND password = ?";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->user_name, $this->password]);
        } catch (\Throwable $exception) {

        }

        return $query->fetchColumn();
    }
}