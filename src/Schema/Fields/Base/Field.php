<?php

namespace Database\Schema\Fields\Base;

abstract class Field
{
    protected string $name;
    protected string $type;
    protected array $traits = ['NOT NULL'];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function render(): string
    {
        return "$this->name $this->type " . implode(' ', $this->traits);
    }
}