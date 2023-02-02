<?php

namespace Database\Schema\Mysql;

use Database\Database;
use Database\Schema;

class MysqlConstructor extends Schema\Constructor
{
    public function __construct(string $host, string $databaseName, string $username, string $password)
    {
        try {
            $this->pdo = new \PDO("mysql:host={$host};dbname={$databaseName}", $username, $password);
        } catch (\Throwable $error) {
            echo "error: " . $error->getMessage();
            die;
        }
    }

    public function getDatabase(): Database
    {
        return new Database($this->pdo);
    }

    
    public function getBlueprintBuilder(): Schema\Blueprint
    {
        return new MysqlBlueprint();
    }
}