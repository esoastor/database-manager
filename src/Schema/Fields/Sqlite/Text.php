<?php

namespace Database\Schema\Fields\Sqlite;

use Database\Schema\Fields\Base;

class Text extends Base\Text
{
    protected string $type = 'TEXT';
    protected int $length = 255;
}