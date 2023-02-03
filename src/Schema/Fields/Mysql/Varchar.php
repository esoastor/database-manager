<?php

namespace Database\Schema\Fields\Mysql;

use Database\Schema\Fields\Base;
use Database\Errors;

class Varchar extends Base\Text
{
    private const MAX_LENGTH = 255;
    protected string $type = 'VARCHAR';
    protected int $length = 255;


    public function render(): string
    {
        if ($this->length > self::MAX_LENGTH) {
            throw new Errors\InvalidArguments();
        }
        return parent::render();
    }
}