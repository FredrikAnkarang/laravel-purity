<?php

namespace Abbasudo\Purity\Filters\Strategies\Date;

use Abbasudo\Purity\Filters\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class RelLessThanFilter extends Filter
{
    /**
     * Operator string to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$relLt';

    public function apply(): Closure
    {
        return function ($query) {
            foreach ($this->values as $value) {
                $query->where($this->column, '<', now()->addDays($value)->toDateTimeString());
            }
        };
    }
}
