<?php

namespace PMieleszkiewicz\EloquentFilters\Exceptions;

class FilterNotImplementedException extends \Exception
{
    protected $message = 'Filter not implemented. It should be a class or FilterSet method.';
}
