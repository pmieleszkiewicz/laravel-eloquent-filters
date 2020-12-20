<?php

declare(strict_types=1);

namespace PMieleszkiewicz\EloquentFilters;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply filters according to Eloquent local model scopes
     * https://laravel.com/docs/master/eloquent#local-scopes
     *
     * @param Builder $query
     * @param string $filterSetName
     * @param array $data
     * @return Builder
     */
    public function scopeFilter(Builder $query, string $filterSetName, array $data): Builder
    {
        $modelFilterSets = property_exists($this, 'filterSets') ? $this->filterSets : [];

        if (empty($modelFilterSets)) {
            return $query;
        }

        if (!isset($modelFilterSets[$filterSetName])) {
            return $query;
        }

        foreach ($modelFilterSets as $fieldName => $filterSetClass) {
            $query = (new $filterSetClass)->apply($query, $data);
        }
        return $query;
    }
}
