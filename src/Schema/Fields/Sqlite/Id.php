<?php

namespace Database\Schema\Fields\Sqlite;

use Database\Schema\Fields\Base;

class Id extends Base\Id
{
    protected string $type = 'INTEGER';
    protected array $traits = ['PRIMARY KEY', 'AUTOINCREMENT'];
}