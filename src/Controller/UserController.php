<?php

namespace Controller;

use Model\Entity\User;
use Model\Repository\UserRepository;

class UserController
{
    private const settedCokkie = 'article_app';
    private const cipher = '873dsmf84w84jzf';

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getLogin() {
        $userCookie = $_COOKIE[self::settedCokkie];
        $cookie = explode(':', $userCookie);
        return $cookie[0];
    }

    public function check(): bool
    {
        if ($this->getLogin() != '') {
            $userCookie = $_COOKIE[self::settedCokkie];
            $cookie = explode(':', $userCookie);
            $userPassword = $this->userRepository->findByLogin($cookie[0])->getPassword();
            $codedPassword = $cookie[1];
            return $userPassword == $codedPassword;
        }
        else return false;
    }

    public function login($login, $password) {
        $codedPassword = md5($password . self::cipher);
        $userPassword = $this->userRepository->findByLogin($login)->getPassword();
        if ($userPassword == $codedPassword) {
            setcookie(self::settedCokkie, $login . ':' . $codedPassword, mktime(). time()+60*60*24*1, '/');
        }
    }

    public function register($login, $password) {
        $codedPassword = md5($password . self::cipher);
        setcookie(self::settedCokkie, $login . ':' . $codedPassword, mktime(). time()+60*60*24*1, '/');
        $this->userRepository->addUser(new User($login, $codedPassword));
    }

    public function logout() {
        setcookie(self::settedCokkie, null, -1, '/');
    }
}