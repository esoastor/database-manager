<?php

namespace SqliteOrm\Schema;

#dbTable constructor;
class Constructor
{
    private \PDO $pdo;

    public function __construct(string $databasePath = 'sqlbase.sqlite')
    {
        try {
            $this->pdo = new \PDO('sqlite:' . $databasePath);
        } catch (\Throwable $error) {
            echo "error: " . $error->getMessage();
            die;
        }
    }

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

    public function getTableConnection(string $name)
    {
        return new \SqliteOrm\Connector($name, $this->pdo);
    }
}