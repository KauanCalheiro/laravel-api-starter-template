<?php

namespace App\Helpers\Spatie\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * Filtro customizado para uso com Spatie QueryBuilder que permite verificar a existência de uma relação.
 *
 * Este filtro é útil para aplicar condições como:
 * - `?filter[nome_relacao.exists]=true`  → onde `nome_relacao` é uma relação existente no model, retornando apenas registros que possuem essa relação.
 * - `?filter[nome_relacao.exists]=false` → retornando apenas registros que **não** possuem a relação.
 *
 * Regras:
 * - A chave do filtro deve seguir o padrão `nome_relacao.exists` (por exemplo: `sinteses.exists`).
 * - O valor do filtro deve ser um booleano (`true` ou `false`).
 *
 * Exemplo de uso com `AllowedFilter::custom()`:
 * ```php
 * AllowedFilter::custom('nome_relacao.exists', new RelationExistsFilter()),
 * ```
 *
 * @example ?filter[nome_relacao.exists]=true
 * @example ?filter[nome_relacao.exists]=false
 */
class RelationExistsFilter implements Filter
{
    public const RELATION_SEPARATOR = '.';

    public function __invoke(Builder $query, mixed $value, string $property)
    {
        $relation = strstr($property, self::RELATION_SEPARATOR, true) ?: $property;

        if (empty($relation)) {
            throw new InvalidArgumentException(__('validation.required', ['attribute' => $property]));
        }

        if (!method_exists($query->getModel(), $relation)) {
            throw new InvalidArgumentException(__(
                'model.relation.not_found',
                [
                    'attribute' => $relation,
                    'model'     => get_class($query->getModel()),
                ],
            ));
        }

        if (!is_bool($value)) {
            throw new InvalidArgumentException(__('validation.boolean', ['attribute' => $property]));
        }

        $value
            ? $query->whereHas($relation)
            : $query->whereDoesntHave($relation);
    }
}
