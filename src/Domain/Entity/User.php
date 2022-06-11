<?php

namespace Domain\Entity;

class User extends ActiveRecord
{
    private const TABLE = "user";

    private int $user_id;
    private string $user_name;
    private string $password;

    public static function createNewUser(string $user_id, string $user_name, string $password): User
    {
        $user = new User();
        $user->user_id = $user_id;
        $user->user_name = $user_name;
        $user->password = $password;

        return $user;
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
        $sql = "INSERT INTO " . self::TABLE . "(user_id, user_name, password) VALUE (?, ?, ?)";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->user_id, $this->user_name, $this->password]);
        } catch (\Throwable $exception) {

        }
    }

    public function remove(): void
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE user_id = ? AND user_name = ? AND password = ?";
        $query = $this->getConnection()->prepare($sql);
        try {
            $query->execute([$this->user_id, $this->user_name, $this->password]);
        } catch (\Throwable $exception) {

        }
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @param string $user_name
     */
    public function setUserName(string $user_name): void
    {
        $this->user_name = $user_name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}