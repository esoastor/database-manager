<?php

namespace Database\Schema;

#db fields
interface Blueprint
{
    public function id(string $name = 'id'): Fields\Base\Id;

    public function text(string $name): Fields\Base\Text;

    public function integer(string $name): Fields\Base\Integer;
}
