<?php

namespace Database\Query;

class CountQuery extends BaseQuery
{
    public function execute(): mixed
    {
        return $this->tableManager->execute()->fetchColumn();
    }
}
