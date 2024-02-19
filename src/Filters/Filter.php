<?php

namespace Abbasudo\Purity\Filters;

use Illuminate\Database\Eloquent\Builder;
use Closure;

abstract class Filter implements \Abbasudo\Purity\Contracts\Filter
{
    protected Builder $query;
    protected string  $column;
    protected array   $values;
    protected array   $options;

    public function __construct(Builder $query, string $column, array $values, array $options = [])
    {
        $this->query = $query;
        $this->column = $column;
        $this->values = $values;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public static function operator(): string
    {
        return static::$operator;
    }

    public function applyLastRelation(): Closure
    {
        return function ($field, $query) {
            $query->whereHas($field, function ($query) {
                $this->apply()($query);
            });
        };
    }
}
