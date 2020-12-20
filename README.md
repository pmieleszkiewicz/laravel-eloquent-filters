# Eloquent Filters

Package allows to filter Eloquent models without bloating models classes.
Filters are grouped in groups called filter sets which must be added in Eloquent models.

```php
// User.php
<?php

namespace App\Models;

use PMieleszkiewicz\EloquentFilters\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Filterable;

    protected $filterSets = [
        'admin' => \App\Http\FilterSets\UserAdminFilterSet::class,
        'portal' => \App\Http\FilterSets\UserPortalFilterSet::class,
    ];
}
```
Model must use `Filterable` trait and fill `$filterSets` array. FilterSets can be prefixed with any name which allows to use multiple of them in one model (e.g. they can be named after controller methods)

## Usage

Create class extending `FilterSet` abstract class and implement `Filters` - as separate classes implementing `Filterable` interface or class methods
```php
<?php

declare(strict_types=1);

namespace App\Http\FilterSets;

use App\Http\Filters\User\EmailFilter;
use App\Http\Filters\User\NameFilter;
use Illuminate\Database\Eloquent\Builder;
use PMieleszkiewicz\EloquentFilters\FilterSet;

class UserAdminFilterSet extends FilterSet
{
    protected $filters = [
        'name' => [
            NameFilter::class,
        ],
        'email' => [
            EmailFilter::class,
        ],
        'id' => [
            'filterId',
        ]
    ];

    protected function filterId(Builder $query, string $fieldName, $value): Builder
    {
        return $query->where($fieldName, '>', $value);
    }
}
```
```php
<?php

namespace App\Http\Filters\User;

use PMieleszkiewicz\EloquentFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class NameFilter implements Filter
{
    public function apply(Builder $query, string $fieldName, $value): Builder
    {
        return $query->where($fieldName, 'like', "%{$value}%");
    }
}

```

## Creating filters and filter sets
TODO: Generate filters and filter sets via Artisan command
