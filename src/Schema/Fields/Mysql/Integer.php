<?php

namespace Database\Schema\Fields\Mysql;

use Database\Schema\Fields\Base;

class Integer extends Base\Integer
{
    protected string $type = 'INT';
}