<?php

namespace SqliteOrm\Schema\Fields\Base;

trait NotNull {
    public function notNull()
    {
        $this->traits[] = 'NOT NULL';
        return $this;
    }
}