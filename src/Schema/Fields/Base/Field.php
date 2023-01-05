<?php

namespace SqliteOrm\Schema\Fields\Base;

abstract class Field
{
    protected string $name;
    protected string $type;
    protected array $traits;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function render(): string
    {
        return "$this->name $this->type " . implode(' ', $this->traits);
    }
}