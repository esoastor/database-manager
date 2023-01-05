<?php

namespace SqliteOrm\Schema\Fields;

class Integer extends Base\Field
{
    use Base\NotNull;
    
    protected string $type = 'INTEGER';
}