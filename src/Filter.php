<?php

declare(strict_types=1);

namespace PMieleszkiewicz\EloquentFilters;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public function apply(Builder $query, string $fieldName, $value): Builder;
}
