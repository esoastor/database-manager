<?php

namespace Database;

class WhereBuilder
{
    private array $condition;
    private array $values;

    public function __construct(private TableManager $tableManager)
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
            $this->addCondition($conditions[0], '=', $conditions[1]);
        } elseif ($conditionsCount === 3) {
            $this->addCondition($conditions[0], $conditions[1], $conditions[2]);
        } else {
            throw new Errors\InvalidArguments();
        }

        return $this;
    }

    protected function addCondition($fieldName, $condition ,$fieldValue): void 
    {
        $this->condition[] = "{$fieldName}{$condition}:{$fieldName}";
        $this->values[$fieldName] = $fieldValue;
    }

    protected function addThreeConditionsCondition():void 
    {

    }

    public function execute(): void
    {
        $this->tableManager->execute();
    }

    public function toString(): string
    {
        $conditionString = '';
        $count = 0;
        foreach ($this->condition as $conditionElement) {
            $conditionString .= $count === 0 ? "WHERE $conditionElement" : "AND $conditionElement";
            $count ++;
        }
        return $conditionString;
    }

    public function getValues(): array
    {
        return $this->values ?? [];
    }

    protected function resetValues(): void
    {
        $this->values = [];
    }

    protected function resetCondition(): void
    {
        $this->condition = [];
    }
}