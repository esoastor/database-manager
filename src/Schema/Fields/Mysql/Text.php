<?php

namespace Database\Schema\Fields\Mysql;

use Database\Schema\Fields\Base;

class Text extends Base\Text
{
    private const MAX_LENGTH = 65535;
    protected string $type = 'TEXT';
    protected int $length = 255;


    public function render(): string
    {
        if ($this->length > self::MAX_LENGTH) {
            throw new \Exception();
        }
        return parent::render();
    }
}