<?php

namespace Database\Schema\Fields\Base;

abstract class Id extends Field
{
    protected string $type = 'INTEGER';
    protected array $traits = ['PRIMARY KEY', 'AUTOINCREMENT'];

    public function __construct($name)
    {
        return parent::__construct($name);
    }
}