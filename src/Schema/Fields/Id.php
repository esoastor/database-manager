<?php

namespace SqliteOrm\Schema\Fields;

class Id extends Base\Field
{
    protected string $type = 'INTEGER';
    protected array $traits = ['PRIMARY KEY', 'AUTOINCREMENT'];

    public function __construct($name)
    {
        return parent::__construct($name);
    }
}