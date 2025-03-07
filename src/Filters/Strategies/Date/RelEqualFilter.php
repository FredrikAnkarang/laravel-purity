<?php

namespace Abbasudo\Purity\Filters\Strategies\Date;

use Abbasudo\Purity\Filters\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class RelEqualFilter extends EqualFilter
{
    /**
     * Operator string to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$relEq';

    public function __construct(Builder $query, string $column, array $values, array $options = [])
    {
        parent::__construct(
            $query,
            $column,
            array_map(fn ($value) => now()->addDays($value)->toDateString(), $values),
            $options
        );
    }
}
