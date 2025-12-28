<?php

namespace App\config;
use MongoDB\Client;

final class MongoDB
{

    private const DB_NAME = 'recipe-app';

    private static function getClient(): Client
    {
        $uri = getenv('MONGODB_URI') ?: "mongodb://localhost:27017";
        return new Client($uri);
    }
    public static function getConnection($dbName = self::DB_NAME)
    {
        $db = getenv('MONGODB_DB') ?: $dbName;
        return self::getClient()->$db;
    }

}