<?php

namespace App\Helpers\Spatie\QueryBuilder\Filters\Search\Strategies;

use Illuminate\Database\Eloquent\Builder;

interface SearchStrategyInterface
{
    public function apply(Builder $query, array $columns, string $search): void;
}
