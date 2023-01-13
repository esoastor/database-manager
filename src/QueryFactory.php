<?php
namespace Database;

use Database\Query\BaseQuery;

class QueryFactory
{
    private BaseQuery $query;

    public function setQuery(BaseQuery $query) {
        $this->query = $query;
    }

    public function getQuery(): BaseQuery {
        return $this->query;
    }
}