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

    public function save(): bool
    {
        $sql = "INSERT INTO " . self::TABLE . "(user_name, password) VALUE (?, ?)";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->user_name, $this->password]);
        } catch (\Throwable $exception) {

        }

        return $query->rowCount() > 0;
    }

    public function remove(): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE user_id = ?";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->findID()]);
        } catch (\Throwable $exception) {

        }

        return $query->rowCount() > 0;
    }

    public function findID(): int
    {
        $sql = "SELECT user_id FROM " . self::TABLE . " WHERE user_name = ?";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->user_name]);
        } catch (\Throwable $exception) {

        }

        return $query->fetchColumn();
    }

    /**
     * @param string $user_name
     */
    public function setUserName(string $user_name): void
    {
        $this->user_name = $user_name;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setUserInfo(string $user_name, string $password): void
    {
        $this->setUserName($user_name);
        $this->setPassword($password);
    }
}