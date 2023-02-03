<?php

namespace Database\Schema\Fields\Mysql;

use Database\Schema\Fields\Base;

class Id extends Base\Id
{
    protected string $type = 'INT';
    protected array $traits = ['AUTO_INCREMENT'];

    public function render(): string
    {
        $rendered = parent::render();
        return $rendered . ', PRIMARY KEY (' . $this->name . ')';
    }
}