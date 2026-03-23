<?php

namespace App\config;
use MongoDB\Client;

final class MongoDB
{
    private static function getClient(): Client
    {
        $uri = $_ENV['MONGODB_URI'];
        return new Client($uri);
    }
    public static function getConnection($dbName = self::DB_NAME)
    {
        $db = getenv('MONGODB_DB');
        return self::getClient()->$db;
    }

}