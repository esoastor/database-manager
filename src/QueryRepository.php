<?php
namespace Database;

use Database\Query\BaseQuery;

class QueryRepository
{
    private BaseQuery $query;

    public function setQuery(BaseQuery $query) {
        $this->query = $query;
    }

    public function getQuery(): BaseQuery {
        return $this->query;
    }
}