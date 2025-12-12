<?php

namespace App\Traits\Search;

use Illuminate\Database\Eloquent\Builder;

trait HasUniversalTableSearch
{
    /**
     * Apply search filter to the table query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function applySearchToTableQuery(Builder $query): Builder
    {
        $searchQuery = request('tableSearch');

        if ($searchQuery) {
            return $query->universalSearch($searchQuery);
        }

        return $query;
    }
}
