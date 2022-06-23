<?php

namespace Controller;

use Model\Entity\User;
use Model\Repository\UserRepository;
use PDO;

class UserController
{
    private const cookie = 'chat';
    private const salt = '23g19hua189@ja';

    private UserRepository $repository;

    public function __construct()
    {
        $host = "localhost";
        $dbname = "chat";
        $user_name = "fostiriy";
        $pass = "RTrtV0h$";
        $DBH = new PDO("mysql:host=$host;port=3306;dbname=$dbname", $user_name, $pass);
        $DBH->exec("USE chat");
        $this->repository = new UserRepository($DBH);
    }

    public function getLogin()
    {
        if (!isset($_COOKIE[self::cookie])) {
            $userCookie = '';
        } else {
            $userCookie = $_COOKIE[self::cookie];
        }
        $cookie = explode(':', $userCookie);
        return $cookie[0];
    }

    public function check(): bool
    {
        if ($this->getLogin() != '') {
            $userCookie = $_COOKIE[self::cookie];
            $cookie = explode(':', $userCookie);
            $userPassword = $this->repository->getByFieldValue("user_name", $cookie[0])[0]->getPassword();
            $codedPassword = $cookie[1];
            return $userPassword == $codedPassword;
        } else return false;
    }

    public function login($login, $password)
    {
        $codedPassword = sha1($password . self::salt);
        $userPassword = $this->repository->getByFieldValue("user_name", $login)[0]->getPassword();
        if ($userPassword == $codedPassword) {
            setcookie(self::cookie, $login . ':' . $codedPassword, mktime() . time() + 60 * 60 * 24 * 1, '/');
        }
    }

    public function register($login, $password)
    {
        $codedPassword = sha1($password . self::salt);
        setcookie(self::cookie, $login . ':' . $codedPassword, mktime() . time() + 60 * 60 * 24 * 1, '/');
        $this->repository->save(new User($login, $codedPassword));
    }

    public function logout()
    {
        setcookie(self::cookie, null, -1, '/');
    }
}