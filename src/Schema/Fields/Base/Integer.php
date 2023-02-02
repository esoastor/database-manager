<?php

namespace Database\Schema\Fields\Base;

abstract class Integer extends Field
{
    use Nullable;
    
    protected string $type = 'INTEGER';
}