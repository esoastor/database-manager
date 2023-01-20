<?php

namespace Database\Query;

class DeleteQuery extends BaseQuery
{
    public function execute(): mixed
    {
        return $this->tableManager->execute();
    }
}
