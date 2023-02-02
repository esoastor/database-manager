<?php

namespace Database\Schema\Mysql;

use Database\Schema\Blueprint;

use Database\Schema\Fields;

class MysqlBlueprint extends Blueprint
{
    public function id(string $name = 'id'): Fields\Base\Id
    {
        return new Fields\Mysql\Id($name);
    }

    public function text(string $name): Fields\Base\Text
    {
        return new Fields\Mysql\Text($name);
    }

    public function number(string $name): Fields\Base\Integer
    {
        return new Fields\Mysql\Integer($name);
    }
}
