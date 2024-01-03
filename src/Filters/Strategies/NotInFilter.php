<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class NotInFilter extends Filter
{
    /**
     * Operator string to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$notIn';

    /**
     * Apply filter logic to $query.
     *
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            foreach ($this->values as $value) {
                $query->whereNotIn($this->column, $value);
            }
        };
    }

    public function applyLastRelation(): Closure
    {
        return function ($field, $query) {
            $query->whereDoesntHave($field, function ($query) {
                $query->whereIn($this->column, $this->values);
            });
        };
    }
}
