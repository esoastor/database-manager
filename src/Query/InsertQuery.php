<?php

namespace Database\Query;

class InsertQuery extends BaseQuery
{
    public function execute(): mixed
    {
        return $this->tableManager->execute();
    }
}
