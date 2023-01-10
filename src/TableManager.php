<?php

namespace Database;

class TableManager
{
    private WhereBuilder $whereBuilder;

    private string $query = '';
    private array $bindingParams = [];

    public function __construct(private string $tableName, private \PDO $pdo)
    {
        $this->whereBuilder = new WhereBuilder($this);
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

    public function count(): WhereBuilder
    {
        $this->query = "SELECT COUNT(*) FROM $this->tableName";
        return $this->whereBuilder;
    }

    public function select(array $fields = []): WhereBuilder
    {
        $fields = $fields === [] ? '*' : substr(implode(', ' , $fields), 0, -1);
        $this->query = "SELECT $fields FROM $this->tableName";
        return $this->whereBuilder;
    }

    public function update(array $fieldValues): WhereBuilder
    {
        $fieldValues = $this->addPrefixToFieldName('u', $fieldValues);
        $fieldNames = array_keys($fieldValues);

        $fieldSetString = array_reduce($fieldNames, function($result, $fieldName) {
            $result .= "$fieldName=:$fieldName,";
            return $result;
        });

        $this->query = "UPDATE $this->tableName SET " . substr($fieldSetString, 0, -1);
        $this->bindingParams = $fieldValues;

        return $this->whereBuilder;
    }

    public function where(...$conditions): WhereBuilder
    {
        return $this->whereBuilder->where($conditions);
    }

    public function execute()
    {
        $whereStatement = $this->whereBuilder->toString();

        $query = "$this->query $this->tableName $whereStatement";

        $statement = $this->pdo->prepare($query);
        
        if (!empty($this->bindingParams) || !empty($this->whereBuilder->getValues()))
        {
            $bindingParams = array_merge($this?->whereBuilder->getValues(), $this?->bindingParams);
            foreach ($bindingParams as $fieldName => $fieldValue) {
                $statement->bindParam(':'.$fieldName, $fieldValue);
            }
        } 

        $statement->execute();

        $this->clearWhereBuilder();
        $this->clearQueryData();

        return $statement->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    protected function clearWhereBuilder(): void
    {
        $this->whereBuilder->resetValues();
        $this->whereBuilder->resetCondition();
    }

    protected function clearQueryData(): void
    {
        $this->query = '';
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