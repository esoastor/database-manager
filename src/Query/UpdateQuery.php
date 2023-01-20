<?php

namespace Database\Query;

class UpdateQuery extends BaseQuery
{
    public function execute(): mixed
    {
        return $this->tableManager->execute();
    }
}
