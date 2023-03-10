<?php

namespace Database\Schema\Sqlite;

use Database\Schema\Blueprint;
use Database\Schema\Fields;

class SqliteBlueprint implements Blueprint
{
    public function id(string $name = 'id'): Fields\Base\Id
    {
        return new Fields\Sqlite\Id($name);
    }

    public function text(string $name): Fields\Base\Text
    {
        return new Fields\Sqlite\Text($name);
    }

    public function integer(string $name): Fields\Base\Integer
    {
        return new Fields\Sqlite\Integer($name);
    }
}
