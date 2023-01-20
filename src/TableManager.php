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

    public function update(array $fieldValues): Query\UpdateQuery
    {
        $fieldNames = array_keys($fieldValues);

        $fieldSetString = array_reduce($fieldNames, function($result, $fieldName) {
            $result .= "$fieldName=:$fieldName,";
            return $result;
        });

        $query = new Query\UpdateQuery($this, "UPDATE $this->tableName SET " . substr($fieldSetString, 0, -1));

        $this->queryFactory->setQuery($query);
        $this->bindingParams = $fieldValues;

        return $query;
    }

    public function delete(): Query\DeleteQuery
    {
        $query = new Query\DeleteQuery($this, "DELETE FROM $this->tableName");
        $this->queryFactory->setQuery($query);
        return $query;
    }

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
}