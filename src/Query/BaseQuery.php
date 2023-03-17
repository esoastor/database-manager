<?php

namespace Database\Query;

use Database\Errors;
use Database\TableManager;
use Database\Query\Conditions;

abstract class BaseQuery
{
    protected array $conditions = [];
    protected array $values;
    protected string $defaultCondition = '=';

    public function __construct(protected TableManager $tableManager, protected string $queryText)
    {  
    }

    public function where(...$conditionPars): self
    {
        foreach ($conditionPars as $conditionPart) {
            if (!is_string($conditionPart)) {
                throw new Errors\InvalidWhereArguments();
            }
        }

        $conditionsPartsCount = count($conditionPars);

        $prefix = $this->getConditionsCount();

        $fieldName = $conditionPars[0];
        if ($conditionsPartsCount === 2) {
            $condition = $this->defaultCondition;
            $fieldValue = $conditionPars[1];
        } elseif ($conditionsPartsCount === 3) {
            $condition = $conditionPars[1];
            $fieldValue = $conditionPars[2];
        } else {
            throw new Errors\InvalidWhereArguments();
        }

        if ($prefix === 0) {
            $condition = new Conditions\WhereCondition($fieldName, $condition, $prefix);
        } else {
            $condition = new Conditions\AndCondition($fieldName, $condition, $prefix);
        }

        $this->addCondition($condition);
        $this->addValue("{$prefix}_{$fieldName}", $fieldValue);

        return $this;
    }

    public function whereIn(string $fieldName, array $values): self
    {
        foreach ($values as $key => $fieldValue) {
            $prefix = $this->getConditionsCount();
            if ($prefix === 0 && $key === 0) {
                $condition = new Conditions\WhereCondition($fieldName, $this->defaultCondition, $prefix);
            } elseif($prefix > 0 && $key === 0) {
                $condition = new Conditions\AndCondition($fieldName, $this->defaultCondition, $prefix);
            } else {
                $condition = new Conditions\OrCondition($fieldName, $this->defaultCondition, $prefix);
            }
            $this->addCondition($condition);
            $this->addValue("{$prefix}_{$fieldName}", $fieldValue);
        }

        return $this;
    }

    public function execute(): mixed
    {
        return $this->tableManager->execute()->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function toString(): string
    {
        $conditionString = '';

        if (isset($this->conditions)) {
            $count = 0;
            foreach ($this->conditions as $condition) {
                $conditionString .= $condition->toString() . ' ';
                $count ++;
            }
        }

        return $conditionString;
    }

    public function getQueryText(): string
    {
        return $this->queryText ?? '';
    }

    protected function addValue(string $key, string $value)
    {
        $this->values[$key] = $value;
    }

    public function getValues(): array
    {
        return $this->values ?? [];
    }

    public function resetValues(): void
    {
        $this->values = [];
    }

    protected function addCondition(Conditions\BaseCondition $condition): void 
    {
        $this->conditions[] = $condition;
    }

    public function resetConditions(): void
    {
        $this->conditions = [];
    }

    protected function getConditionsCount(): int
    {
        return count($this->conditions);
    }
}