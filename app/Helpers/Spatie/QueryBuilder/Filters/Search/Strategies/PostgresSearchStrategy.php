<?php

namespace App\Helpers\Spatie\QueryBuilder\Filters\Search\Strategies;

use Illuminate\Database\Eloquent\Builder;

class PostgresSearchStrategy implements SearchStrategyInterface
{
    public function apply(Builder $query, array $columns, string $search): void
    {
        $search = '%' . str_replace(' ', '%', $search) . '%';

        $localColumns = [];
        $relations    = [];

        foreach ($columns as $column) {
            if (str_contains($column, '.')) {
                [$relation, $field]     = explode('.', $column);
                $relations[$relation][] = $field;
            } else {
                $localColumns[] = $column;
            }
        }

        if (empty($localColumns) && empty($relations)) {
            return;
        }

        $query->where(function ($subQuery) use ($localColumns, $relations, $search) {
            $hasLocalColumns = !empty($localColumns);
            if ($hasLocalColumns) {
                $this->search($subQuery, $localColumns, $search);
            }

            $hasRelations = !empty($relations);
            if ($hasRelations) {
                foreach ($relations as $relation => $relationColumns) {
                    $this->relationSearch($subQuery, $relation, $relationColumns, $search);
                }
            }
        });
    }

    protected function search(Builder $query, array $columns, string $search): void
    {
        $query->where(function ($subQuery) use ($columns, $search) {
            $table = $subQuery->getModel()->getTable();
            foreach ($columns as $column) {
                $subQuery->orWhereRaw("unaccent(\"{$table}\".\"{$column}\"::TEXT) ILIKE unaccent(?)", [$search]);
            }
        });
    }

    protected function relationSearch(Builder $query, string $relation, array $columns, string $search): void
    {
        $query->orWhereHas($relation, function ($relationQuery) use ($columns, $search) {
            $this->search($relationQuery, $columns, $search);
        });
    }
}
