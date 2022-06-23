<?php

namespace Model\Entity;

class User
{
    private const TABLE = "user";

    private string $user_name;
    private string $password;

    /**
     * @param \PDO $connection
     * @param string $userName
     * @param string $password
     */
    public function __construct(string $userName = "", string $password = "")
    {
        $this->user_name = $userName;
        $this->password = $password;
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

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

}