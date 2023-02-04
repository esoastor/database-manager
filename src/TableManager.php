<?php

namespace Database;

use Database\Query;
use Database\Errors\QueryNotEmpty;

class TableManager
{
    private QueryRepository $queryRepository;

    private array $bindingParams = [];

    public function __construct(private string $tableName, private \PDO $pdo)
    {
        $this->queryRepository = new QueryRepository();
    }

    public function insert(array $fieldValues): Query\InsertQuery
    {
        $this->checkIfQueryEmpty();

        $fieldNames = array_keys($fieldValues);

        $fieldNamesString = implode(',', $fieldNames);
        $fieldAnchorsString = array_reduce($fieldNames, function($result, $fieldName) {
            $result .= ":$fieldName,";
            return $result;
        });

        $query = new Query\InsertQuery($this, "INSERT INTO $this->tableName (" . $fieldNamesString . ") VALUES (" . substr($fieldAnchorsString, 0, -1) . ");");

        $this->queryRepository->setQuery($query);
        $this->bindingParams = $fieldValues;

        return $query;
    }

    public function count(): Query\CountQuery
    {
        $this->checkIfQueryEmpty();

        $query = new Query\CountQuery($this, "SELECT COUNT(*) FROM $this->tableName");

        $this->queryRepository->setQuery($query);
        return $query;
    }

    public function select(array $fields = []): Query\SelectQuery
    {
        $this->checkIfQueryEmpty();

        $fields = $fields === [] ? '*' : implode(', ', $fields);
        $query = new Query\SelectQuery($this, "SELECT $fields FROM $this->tableName");
        $this->queryRepository->setQuery($query);
        return $query;
    }

    public function update(array $fieldValues): Query\UpdateQuery
    {
        $this->checkIfQueryEmpty();

        $fieldNames = array_keys($fieldValues);

        $fieldSetString = array_reduce($fieldNames, function ($result, $fieldName) {
            $result .= "$fieldName=:$fieldName,";
            return $result;
        });

        $query = new Query\UpdateQuery($this, "UPDATE $this->tableName SET " . substr($fieldSetString, 0, -1));

        $this->queryRepository->setQuery($query);
        $this->bindingParams = $fieldValues;

        return $query;
    }

    public function delete(): Query\DeleteQuery
    {
        $this->checkIfQueryEmpty();

        $query = new Query\DeleteQuery($this, "DELETE FROM $this->tableName");
        $this->queryRepository->setQuery($query);
        return $query;
    }

    protected function checkIfQueryEmpty(): void
    {
        if(!$this->queryRepository->isEmpty()) {
            throw new QueryNotEmpty();
        }
    }

    public function execute(): mixed
    {
        $query = $this->queryRepository->getQuery();

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

        $this->clearQuery();
        $this->clearBindingParams();
        $this->queryRepository->unsetQuery();

        return $statement;
    }

    protected function clearQuery(): void
    {
        $query = $this->queryRepository->getQuery();
        $query->resetValues();
        $query->resetCondition();
    }

    protected function clearBindingParams(): void
    {
        $this->bindingParams = [];
    }
}
