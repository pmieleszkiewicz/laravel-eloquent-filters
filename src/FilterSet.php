<?php

declare(strict_types=1);

namespace PMieleszkiewicz\EloquentFilters;

use Illuminate\Database\Eloquent\Builder;
use PMieleszkiewicz\EloquentFilters\Exceptions\FilterNotImplementedException;

abstract class FilterSet
{
    /**
     * Should be filled in a child class
     *
     * @var array
     */
    protected $filters = [];

    /**
     * @param Builder $query
     * @param array $data
     * @return Builder
     * @throws \Exception
     */
    public function apply(Builder $query, array $data): Builder
    {
        if (empty($data)) {
            return $query;
        }

        foreach ($data as $field => $value) {
            if (!$this->filtersForFieldExists($field)) {
                continue;
            }
            foreach ($this->filters[$field] as $filter) {
                $query = $this->applyFilterToQuery($filter, $query, $field, $value);
            }
        }

        return $query;
    }

    /**
     * @param string $field
     * @return bool
     */
    protected function filtersForFieldExists(string $field): bool
    {
        return array_key_exists($field, $this->filters);
    }

    /**
     * Filter can be a full namespaced class string or method name
     *
     * @param string $filter
     * @param Builder $query
     * @param string $field
     * @param $value
     * @return Builder
     * @throws \Exception
     */
    protected function applyFilterToQuery(string $filter, Builder $query, string $field, $value): Builder
    {
        if (method_exists($this, $filter)) {
            $query = $this->$filter($query, $field, $value);
        } elseif (class_exists($filter))  {
            $query = (new $filter)->apply($query, $field, $value);
        } else {
            throw new FilterNotImplementedException();
        }

        return $query;
    }
}
