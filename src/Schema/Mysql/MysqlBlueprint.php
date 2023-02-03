<?php

namespace Database\Schema\Mysql;

use Database\Schema\Blueprint;

use Database\Schema\Fields;

class MysqlBlueprint implements Blueprint
{
    public function id(string $name = 'id'): Fields\Base\Id
    {
        return new Fields\Mysql\Id($name);
    }

    public function text(string $name): Fields\Base\Text
    {
        return new Fields\Mysql\Text($name);
    }

    public function varchar(string $name): Fields\Base\Text
    {
        return new Fields\Mysql\Varchar($name);
    }

    public function tinyInteger(string $name): Fields\Base\Integer
    {
        return new Fields\Mysql\TinyInteget($name);
    }

    public function smallInteger(string $name): Fields\Base\Integer
    {
        return new Fields\Mysql\SmallIneger($name);
    }
    
    public function integer(string $name): Fields\Base\Integer
    {
        return new Fields\Mysql\Integer($name);
    }
}
