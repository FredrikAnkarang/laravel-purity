<?php

namespace Abbasudo\Purity\Filters\Strategies\Date;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class EqualFilter extends Filter
{
    protected static string $operator = '$dateEq';

    /**
     * Apply filter logic to $query.
     *
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            foreach ($this->values as $value) {
                $query->whereDate($this->column, $value);
            }
        };
    }
}
