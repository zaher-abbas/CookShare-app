<?php

namespace App\config;

use PDO;
use PDOException;
use RuntimeException;

final class MySQL
{
    public static function getConnection(): ?PDO
    {
        try {
            $host = $_ENV['MYSQL_HOST'];
            $dbname = $_ENV['MYSQL_DB'];
            $user = $_ENV['MYSQL_USER'];
            $pass = $_ENV['MYSQL_PASSWORD'];

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (
            PDOException | RuntimeException $e
        )
        {
            return null;
        }
    }
}
