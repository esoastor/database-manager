<?php

namespace Database\Schema\Fields\Mysql;

use Database\Schema\Fields\Base;

class SmallIneger extends Base\Integer
{
    protected string $type = 'SMALLINT';
}