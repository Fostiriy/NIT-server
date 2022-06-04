<?php

declare(strict_types=1);

namespace Application\Web;

class UserDataController
{
    private const SALT = '9018c2urm8h40g2yhc12';
    private const USER_COOKIE = 'not_user_data';

    private const USERS = [ // вместо БД
        'vova' => '4255ede5530856819a81ab1548188fd9',
    ];

    public function reg(string $login, string $password)
    {
        $encryptedPassword = md5($password . self::SALT);
        setcookie(self::USER_COOKIE, $login . ':' . $encryptedPassword, 3600, '/'); // тут же сохранение в БД
        echo $encryptedPassword;
    }

    public function verify(): bool
    {
        $userCookie = $_COOKIE[self::USER_COOKIE];
        $data = explode(':', $userCookie);
        // дальше в БД
        $userPass = self::USERS[$data[0]];
        $encrPass = md5($data[1] . self::SALT);
        return $userPass === $encrPass;
    }
}