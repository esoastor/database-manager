<?php

namespace Database;

class Database
{
    public function __construct(protected \PDO $pdo)
    {
    }
    
    public function table(string $name): \Database\TableManager
    {
        return new \Database\TableManager($name, $this->pdo);
    }

}