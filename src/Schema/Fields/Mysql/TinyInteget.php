<?php

namespace Database\Schema\Fields\Mysql;

use Database\Schema\Fields\Base;

class TinyInteget extends Base\Integer
{
    protected string $type = 'TINYINT';
}