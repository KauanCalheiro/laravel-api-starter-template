<?php

namespace App\Helpers\Spatie\QueryBuilder\Sorts;

use App\Contracts\JoinableContract;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class RelationColumnSort implements Sort
{
    /**
     * Invoke the sort function
     *
     * @param  Builder $query
     * @param  boolean $descending
     * @param  string  $property
     *
     * @return void
     *
     * @example AllowedSort::custom('centro_custo.nome', new RelationColumnSort(), 'centroCusto.nome')
     * 'centro_custo' is how it will be called by the API
     * 'centroCusto' is what the function receives in $property
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@univates.br>
     *
     * @date 2025-01-21
     */
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $model                          = $query->getModel();
        list($relation, $relatedColumn) = explode('.', $property);
        $relatedTable                   = $model->$relation()->getRelated()->getTable();

        if (! ($model instanceof JoinableContract)) {
            throw new \Exception('Model must implement JoinableContract');
        }

        $model->scopeApplyJoins($query);

        $query->orderBy("{$relatedTable}.{$relatedColumn}", $descending ? 'desc' : 'asc');
    }
}
