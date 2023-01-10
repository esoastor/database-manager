<?php

namespace Database\Schema\Fields;

class Integer extends Base\Field
{
    use Base\Nullable;
    
    protected string $type = 'INTEGER';
}