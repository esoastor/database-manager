<?php

namespace Database\Schema\Fields\Base;

abstract class Field
{
    protected string $name;
    protected string $type;
    protected array $traits = [];
    protected string $notNullTrait = 'NOT NULL';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function render(): string
    {
        $queryString =  "$this->name $this->type";
        $queryString .=  $this->notNullTrait ? ' ' .  $this->notNullTrait : '';
        $queryString .= !empty($this->traits) ? ' ' . implode(' ', $this->traits) : '';
        return $queryString;
    }
}