<?php

namespace Database\Schema\Fields\Base;

trait Nullable {
    public function nullable()
    {
        foreach ($this->traits as $key => $trait) {
            if ($trait === 'NOT NULL') {
                unset($this->traits[$key]);
            }
        }
        return $this;
    }
}