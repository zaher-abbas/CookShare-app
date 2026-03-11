<?php

namespace App\config;


use PDO;
use PDOException;

final class MySQL
{
    private const HOST = 'localhost';
    private const DBNAME = 'cookshare-app';
    private const USER = 'zaher';
    private const PASSWORD = '6666';

    public static function getConnection(): ?PDO
    {
        try {
            $host = getenv('MYSQL_HOST') ?: self::HOST;
            $dbname = getenv('MYSQL_DB') ?: self::DBNAME;
            $user = getenv('MYSQL_USER') ?: self::USER;
            $pass = getenv('MYSQL_PASSWORD') ?: self::PASSWORD;

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return null;
    }
}
