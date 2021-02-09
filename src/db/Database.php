<?php


namespace db;


class Database
{
    private static $conn = null;

    public static function getConnection(){
        if(is_null(self::$conn)){
            $db = ['dsn' => 'mysql:host=127.0.0.1;dbname=sparetimehotel;charset=utf8', 'user' => 'root', 'password' => ''];
            self::$conn = new \PDO($db['dsn'], $db['user'], $db['password']);
        }
        return self::$conn;
    }
}