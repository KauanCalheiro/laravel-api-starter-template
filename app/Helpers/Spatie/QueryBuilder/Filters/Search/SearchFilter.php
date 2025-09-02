<?php

namespace App\Helpers\Spatie\QueryBuilder\Filters\Search;

use App\Enums\DriverEnum;
use App\Helpers\Spatie\QueryBuilder\Filters\Search\Strategies\PostgresSearchStrategy;
use App\Helpers\Spatie\QueryBuilder\Filters\Search\Strategies\SearchStrategyInterface;
use App\Helpers\Spatie\QueryBuilder\Filters\Search\Strategies\SqliteSearchStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * Filtro customizado para uso com Spatie QueryBuilder que permite realizar buscas em múltiplas colunas de um model e suas relações.
 *
 * Este filtro é útil para aplicar condições como:
 * - `?filter[search]='term'`  → onde `term` é o termo de busca, retornando apenas registros que possuem esse termo em uma das colunas especificadas.
 *
 * Regras:
 * - A chave do filtro deve seguir o padrão `search` (ou seja, `?filter[search]=...`).
 * - O valor do filtro deve ser uma string.
 *
 * Exemplo de uso com `AllowedFilter::custom()`:
 * ```php
 * AllowedFilter::custom('search', new SearchFilter(['nome', 'descricao', 'relacao.campo'])),
 * ```
 *
 * @example ?filter[search]='term'
 */
class SearchFilter implements Filter
{
    protected SearchStrategyInterface $strategy;
    protected array $columnsToSearch;

    protected const SEARCH_PROPERTY = 'search';

    public function __construct(array $columnsToSearch)
    {
        $this->columnsToSearch = $columnsToSearch;
        $this->strategy        = $this->resolveStrategy(DB::getDriverName());
    }

    protected function resolveStrategy(string $driver): SearchStrategyInterface
    {
        return match ($driver) {
            DriverEnum::POSTGRES->value => new PostgresSearchStrategy(),
            DriverEnum::SQLITE->value   => new SqliteSearchStrategy(),
            default                     => throw new RuntimeException(__(
                'database.unsupported.driver',
                ['driver' => $driver],
            )),
        };
    }

    public function __invoke(Builder $query, mixed $value, string $property)
    {
        if ($property !== self::SEARCH_PROPERTY) {
            throw new InvalidArgumentException(__(
                'validation.must_be',
                ['attribute' => $property, 'value' => self::SEARCH_PROPERTY],
            ));
        }

        if (empty($value)) {
            return;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(__('validation.string', ['attribute' => $property]));
        }

        $this->strategy->apply($query, $this->columnsToSearch, $value);
    }
}
