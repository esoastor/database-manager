<?php

namespace SqliteOrm;

class Connector
{
    public function __construct(private string $tableName, private \PDO $pdo)
    {
    }

    public function insert(array $fieldValues)
    {
        $fieldNames = array_keys($fieldValues);

        $fieldNamesString = implode(',', $fieldNames);
        $fieldAnchorsString = array_reduce($fieldNames, function($result, $fieldName) {
            $result .= ":$fieldName,";
            return $result;
        });

        $query = "INSERT INTO $this->tableName (" . $fieldNamesString . ") VALUES (" . substr($fieldAnchorsString, 0, -1) . ")";

        $statement = $this->pdo->prepare($query);
        $statement->execute($fieldValues);
    }
}