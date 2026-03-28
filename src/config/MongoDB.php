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
    public static function getConnection()
    {
        $db = $_ENV['MONGODB_DB'];
        return self::getClient()->$db;
    }

}