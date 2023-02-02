<?php

namespace Database\Schema;

#db fields
abstract class Blueprint
{
    abstract public function id(string $name = 'id'): Fields\Base\Id;

    abstract public function text(string $name): Fields\Base\Text;

    abstract public function number(string $name): Fields\Base\Integer;
}
