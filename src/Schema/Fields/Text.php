<?php

namespace Database\Schema\Fields;

class Text extends Base\Field
{
    use Base\NotNull;

    protected string $type = 'TEXT';
    protected int $length = 255;

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