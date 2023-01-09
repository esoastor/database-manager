<?php

namespace Database\Schema;

#db fields
class Blueprint
{
    public function id(string $name = 'id'): Fields\Id
    {
        return new Fields\Id($name);
    }

    public function text(string $name): Fields\Text
    {
        return new Fields\Text($name);
    }

    public function number(string $name): Fields\Integer
    {
        return new Fields\Integer($name);
    }
}
