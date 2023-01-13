<?php

namespace Database;

use Database\Query;

class TableManager
{
    private QueryFactory $queryFactory;

    private array $bindingParams = [];

    public function __construct(private string $tableName, private \PDO $pdo)
    {
        $this->queryFactory = new QueryFactory();
    }

    public function insert(array $fieldValues): void
    {
        $fieldNames = array_keys($fieldValues);

        $fieldNamesString = implode(',', $fieldNames);
        $fieldAnchorsString = array_reduce($fieldNames, function($result, $fieldName) {
            $result .= ":$fieldName,";
            return $result;
        });

        $query = "INSERT INTO $this->tableName (" . $fieldNamesString . ") VALUES (" . substr($fieldAnchorsString, 0, -1) . ");";
        $statement = $this->pdo->prepare($query);
        $statement->execute($fieldValues);
    }

    public function count(): Query\CountQuery
    {
        $query = new Query\CountQuery($this, "SELECT COUNT (*) FROM $this->tableName");
        $this->queryFactory->setQuery($query);
        return $query;
    }

    public function select(array $fields = []): Query\SelectQuery
    {

        $fields = $fields === [] ? '*' : implode(', ' , $fields);
        $query = new Query\SelectQuery($this, "SELECT $fields FROM $this->tableName");
        $this->queryFactory->setQuery($query);
        return $query;
    }

    // public function update(array $fieldValues): BaseQueryBuilder
    // {
    //     $fieldValues = $this->addPrefixToFieldName('u', $fieldValues);
    //     $fieldNames = array_keys($fieldValues);

    //     $fieldSetString = array_reduce($fieldNames, function($result, $fieldName) {
    //         $result .= "$fieldName=:$fieldName,";
    //         return $result;
    //     });

    //     $this->query = "UPDATE $this->tableName SET " . substr($fieldSetString, 0, -1);
    //     $this->bindingParams = $fieldValues;

    //     return $this->whereBuilder;
    // }

    public function execute(): mixed
    {
        $query = $this->queryFactory->getQuery();

        $whereStatement = $query->toString();

        $queryText = $query->getQueryText();
        $queryText .= $whereStatement !== '' ? " $whereStatement;" : ';';

        $statement = $this->pdo->prepare($queryText);

        $queryValues = $query->getValues();

        if (!empty($this->bindingParams) || !empty($queryValues))
        {
            $bindingParams = array_merge($queryValues, $this?->bindingParams);
            foreach ($bindingParams as $fieldName => $fieldValue) {
                $statement->bindValue($fieldName, $fieldValue);
            }
        } 

        $statement->execute();

        $this->clearBaseQueryBuilder();
        $this->clearQueryData();

        return $statement;
    }

    protected function clearBaseQueryBuilder(): void
    {
        $query = $this->queryFactory->getQuery();
        $query->resetValues();
        $query->resetCondition();
    }

    protected function clearQueryData(): void
    {
        $this->bindingParams = [];
    }

    protected function addPrefixToFieldName(string $prefix, array $fields): array
    {
        $updatedArray = [];
        foreach ($fields as $key => $value) {
            $updatedArray["$prefix" . '_' . "$key"] = $value;
        }
        return $updatedArray;
    }
}