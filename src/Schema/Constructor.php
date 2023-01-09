<?php

namespace Database\Schema;

#dbTable constructor;
abstract class Constructor
{
    protected \PDO $pdo;

    public function createTable(string $name, array $fields) 
    {
        $query = "CREATE TABLE IF NOT EXISTS $name (";
        foreach($fields as $field) {
            if ($field instanceof Fields\Base\Field) {
                $query .= $field->render() . ',';
            }
        }
        $query = substr($query, 0, -1);
        $query .= ');';

        $this->pdo->exec($query);
    }

    public function getPdoDriver(): \PDO
    {
        return $this->pdo;
    }
}