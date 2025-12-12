<?php

namespace App\Traits\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait HasUniversalSearch
{
    /**
     * Columns to exclude from universal search
     */
    protected array $searchExcludedColumns = [
        'id',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get searchable columns for the model
     */
    public function getSearchableColumns(): array
    {
        $columns = Schema::getColumnListing($this->getTable());

        // Remove excluded columns
        $excludedColumns = array_merge(
            $this->searchExcludedColumns,
            $this->getCustomExcludedColumns()
        );

        return array_diff($columns, $excludedColumns);
    }

    /**
     * Override this method in your model to exclude specific columns
     */
    protected function getCustomExcludedColumns(): array
    {
        return [];
    }

    /**
     * Scope for universal search functionality
     */
    public function scopeUniversalSearch(Builder $query, string $search): Builder
    {
        if (empty(trim($search))) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $columns = $this->getSearchableColumns();

            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$search}%");
            }
        });
    }
}
