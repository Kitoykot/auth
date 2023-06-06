<?php

namespace AuthApp;

class Connect
{
    private static $host = "127.0.0.1";
    private static $user = "root";
    private static $password = "";
    private static $name = "auth";

    public static function db()
    {
        return mysqli_connect(self::$host, self::$user, self::$password, self::$name);
    }
}