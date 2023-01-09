<?php

namespace Database\Schema\Fields\Base;

trait NotNull {
    public function notNull()
    {
        $this->traits[] = 'NOT NULL';
        return $this;
    }
}