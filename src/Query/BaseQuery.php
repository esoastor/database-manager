<?php

namespace Database\Query;

use Database\Errors;
use Database\TableManager;

abstract class BaseQuery
{
    protected array $condition;
    protected array $values;

    public function __construct(protected TableManager $tableManager, protected string $queryText)
    {  
    }

    public function where(...$conditions): self
    {
        foreach ($conditions as $conditionPart) {
            if (!is_string($conditionPart)) {
                throw new Errors\InvalidWhereArguments();
            }
        }

        $conditionsCount = count($conditions);

        if ($conditionsCount === 2) {
            $this->addCondition($conditions[0], '=', $conditions[1]);
        } elseif ($conditionsCount === 3) {
            $this->addCondition($conditions[0], $conditions[1], $conditions[2]);
        } else {
            throw new Errors\InvalidWhereArguments();
        }

        return $this;
    }

    protected function addCondition(string $fieldName, string $condition, string $fieldValue): void 
    {
        $this->condition[] = "{$fieldName} {$condition} :w_{$fieldName}";
        $this->values['w_' . $fieldName] = $fieldValue;
    }

    public function execute(): mixed
    {
        return $this->tableManager->execute()->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function toString(): string
    {
        $conditionString = '';

        if (isset($this->condition)) {
            $count = 0;
            foreach ($this->condition as $conditionElement) {
                $conditionString .= $count === 0 ? "WHERE $conditionElement" : " AND $conditionElement";
                $count ++;
            }
        }

        return $conditionString;
    }

    public function getQueryText(): string
    {
        return $this->queryText ?? '';
    }

    public function getValues(): array
    {
        return $this->values ?? [];
    }

    public function resetValues(): void
    {
        $this->values = [];
    }

    public function resetCondition(): void
    {
        $this->condition = [];
    }
}