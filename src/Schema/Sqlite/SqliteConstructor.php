<?php

namespace Database\Schema\Sqlite;

use Database\Database;
use Database\Schema;

class SqliteConstructor extends Schema\Constructor
{
    public function __construct(string $databaseName = 'sqlbase.sqlite')
    {
        try {
            $this->pdo = new \PDO('sqlite:' . $databaseName);
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
        return new SqliteBlueprint();
    }
}