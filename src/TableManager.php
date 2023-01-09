<?php

namespace Database;

class TableManager
{
    private QueryBuilder $queryBuilder;

    public function __construct(private string $tableName, private \PDO $pdo)
    {
        $this->queryBuilder = new QueryBuilder($tableName, $pdo);
    }

    public function create(array $fieldValues): void
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

    public function where(...$conditions): QueryBuilder
    {
        return $this->queryBuilder->where($conditions);
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