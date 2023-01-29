<?php

namespace Database\Schema;

use Database\Database;

class SqliteConstructor extends Constructor
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
}