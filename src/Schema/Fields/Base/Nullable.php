<?php

namespace Database\Schema\Fields\Base;

trait Nullable {
    public function nullable()
    {
        $this->notNullTrait = '';

        return $this;
    }
}