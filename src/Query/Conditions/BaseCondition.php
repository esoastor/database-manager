<?php

namespace Database\Query\Conditions;

abstract class BaseCondition
{
    protected string $condition;
    protected string $conditionString;
    
    public function __construct(string $fieldName, string $condition, string $prefix) {
        $this->conditionString = "{$this->condition} {$fieldName} {$condition} :{$prefix}_{$fieldName}";
    }

    public function toString(): string
    {
        return $this->conditionString;
    }
}