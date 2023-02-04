<?php
namespace Database;

use Database\Query\BaseQuery;

class QueryRepository
{
    private BaseQuery $query;

    public function isEmpty(): bool
    {
        return !isset($this->query);
    }

    public function setQuery(BaseQuery $query) {
        $this->query = $query;
    }

    public function getQuery(): BaseQuery {
        return $this->query;
    }

    public function unsetQuery(): void {
        unset($this->query);
    }
}