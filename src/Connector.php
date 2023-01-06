<?php

namespace SqliteOrm;

class Connector
{
    public function __construct(private string $tableName, private \PDO $pdo)
    {
    }

    public function insert(array $fieldValues): void
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

    # by one field
    public function find(array $field): array
    {
        $fieldName = array_keys($field)[0];

        $query = "SELECT * FROM $this->tableName WHERE $fieldName=:$fieldName";

        $statement = $this->pdo->prepare($query);
        
        foreach ($field as $fieldName => $fieldValue) {
            $statement->execute([$fieldName => $fieldValue]);
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function updateById(int $id, array $fieldValues): void
    {
        $fieldNames = array_keys($fieldValues);

        $fieldSetString = array_reduce($fieldNames, function($result, $fieldName) {
            $result .= "$fieldName = :$fieldName,";
            return $result;
        });
        $query = "UPDATE $this->tableName SET " . substr($fieldSetString, 0, -1) . " WHERE id=:id";

        $statement = $this->pdo->prepare($query);

        $statement->execute(array_merge(['id' => $id], $fieldValues));    
    }
}