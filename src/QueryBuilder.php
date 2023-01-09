<?php

namespace Database;

class QueryBuilder
{
    private array $query;
    private array $values;

    public function __construct(private string $tableName, private \PDO $pdo)
    {  
    }

    public function where(...$conditions): self
    {
        $conditions = $conditions[0];
        foreach ($conditions as $conditionPart) {
            if (!is_string($conditionPart)) {
                throw new Errors\InvalidArguments();
            }
        }

        $conditionsCount = count($conditions);

        if ($conditionsCount === 2) {
            $this->addEqualQuery($conditions[0], $conditions[1]);
        } elseif ($conditionsCount === 3) {
            $this->addThreeConditionsQuery($conditions[0], $conditions[1], $conditions[2]);
        } else {
            throw new Errors\InvalidArguments();
        }

        return $this;
    }

    private function addEqualQuery($fieldName, $fieldValue): void 
    {
        $this->query[] = "$fieldName=:$fieldName";
        $this->values[$fieldName] = $fieldValue;
    }

    private function addThreeConditionsQuery():void 
    {

    }

    public function get(): array
    {
        $whereStatement = $this->queryToString();

        $query = "SELECT * FROM $this->tableName $whereStatement";

        $statement = $this->pdo->prepare($query);
        
        foreach ($this->values as $fieldName => $fieldValue) {
            $statement->execute([$fieldName => $fieldValue]);
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    private function queryToString(): string
    {
        $queryString = '';
        $count = 0;
        foreach ($this->query as $queryElement) {
            $queryString .= $count === 0 ? "WHERE $queryElement" : "AND $queryElement";
            $count ++;
        }
        
        $this->query = [];

        return $queryString;
    }
}