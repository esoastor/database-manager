<?php

namespace Database\Schema\Fields\Base;

abstract class Text extends Field
{
    use Nullable;

    protected string $type;
    protected int $length;

    public function length(int $length)
    {
        $this->length = $length;
        return $this;
    }

    public function render(): string
    {
        $this->type = "$this->type($this->length)";
        return parent::render();
    }
}