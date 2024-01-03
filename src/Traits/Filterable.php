<?php

namespace Abbasudo\Purity\Traits;

use Abbasudo\Purity\Filters\FilterList;
use Abbasudo\Purity\Filters\Resolve;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

/**
 * List of available filters, can be set on the model otherwise it will be read from config.
 *
 * @property array $filters
 *
 * List of available fields, if not declared will accept every thing.
 * @property array $filterFields
 */
trait Filterable
{
    use getColumns;

    /**
     * Apply filters to the query builder instance.
     *
     * @param Builder    $query
     * @param array|null $params
     *
     * @throws Exception
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, array|null $params = null): Builder
    {
        $this->bootFilter();

        if (!isset($params)) {
            // Retrieve the filters from the request query
            $params = request()->query('filters', []);
        }

        // Apply each filter to the query builder instance

        $query->where(function ($query) use ($params) {
            foreach ($params as $field => $value) {
                app(Resolve::class)->apply($query, $field, $value);
            }
        });

        return $query;
    }

    /**
     * boots filter bindings.
     *
     * @return void
     */
    private function bootFilter(): void
    {
        app()->singleton(FilterList::class, function () {
            return (new FilterList())->only($this->getFilters());
        });

        app()->when(Resolve::class)->needs(Model::class)->give(fn () => $this);
    }

    /**
     * @return array
     */
    private function getFilters(): array
    {
        return $this->filters ?? config('purity.filters');
    }

    /**
     * @param Builder      $query
     * @param array|string $filters
     *
     * @return Builder
     */
    public function scopeFilterBy(Builder $query, array|string $filters): Builder
    {
        $this->filters = is_array($filters) ? $filters : array_slice(func_get_args(), 1);

        return $query;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getField(string $field): string
    {
        return $this->realName($this->availableFields(), $field);
    }

    /**
     * @return array
     */
    public function availableFields(): array
    {
        return $this->filterFields ?? array_merge($this->getTableColumns(), $this->relations());
    }

    /**
     *  list models relations.
     *
     * @return array
     */
    private function relations(): array
    {
        $methods = (new ReflectionClass(get_called_class()))->getMethods();

        return collect($methods)
            ->filter(
                fn ($method) => !empty($method->getReturnType()) &&
                    str_contains(
                        $method->getReturnType(),
                        'Illuminate\Database\Eloquent\Relations'
                    )
            )
            ->map(fn ($method) => $method->name)
            ->values()->all();
    }

    /**
     * @param Builder      $query
     * @param array|string $fields
     *
     * @return Builder
     */
    public function scopeFilterFields(Builder $query, array|string $fields): Builder
    {
        $this->filterFields = is_array($fields) ? $fields : array_slice(func_get_args(), 1);

        return $query;
    }
}
