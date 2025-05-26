<?php

namespace App\Services;

use PDO;

class DbConnection
{
    private PDO $pdo;

    public function __construct()
    {
        $host = getenv('MYSQL_HOST') ?: 'mysql';
        $db   = getenv('MYSQL_DATABASE');
        $user = getenv('MYSQL_USER');
        $pass = getenv('MYSQL_PASSWORD');

        $this->pdo = new PDO(
            "mysql:host={$host};dbname={$db};charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}