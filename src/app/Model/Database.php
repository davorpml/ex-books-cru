<?php

declare(strict_types=1);

namespace App\Model;

class Database
{
    private $conn = null;

    public function __construct()
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};charset=utf8;dbname={$_ENV['DB_DATABASE']}";

        try {
            $this->conn = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
        } catch (\PDOException $e){
            error_log($e->getMessage());
        }

    }

    public function getConnection(): ?\PDO
    {
        return $this->conn;
    }

}