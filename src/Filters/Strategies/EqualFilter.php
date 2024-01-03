<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class EqualFilter extends Filter
{
    /**
     * Operator string to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$eq';

    /**
     * Apply filter logic to $query.
     *
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            foreach ($this->values as $value) {
                $query->where($this->column, $value);
            }
        };
    }

    public function applyLastRelation(): Closure
    {
        return function ($field, $query) {
            foreach ($this->values as $value) {
                $query->whereHas($field, function ($query) use ($value) {
                    $query->where($this->column, $value);
                });
            }
        };
    }
}
