<?php

namespace Abbasudo\Purity\Filters\Strategies\Date;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class GreaterThanFilter extends Filter
{
    /**
     * Operator string to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$dateGt';

    /**
     * Apply filter logic to $query.
     *
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            foreach ($this->values as $value) {
                $query->whereDate($this->column, '>', $value);
            }
        };
    }
}
