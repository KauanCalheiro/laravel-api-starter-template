<?php

namespace App\Helpers\Spatie\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use RuntimeException;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * Filter que aplica regras baseadas em permissões do usuário atual.
 *
 * Este filtro chama o método `scopeAllowed` da model, que deve ser implementado
 * para definir as restrições (por exemplo, por papel do usuário).
 *
 * Exemplo de uso:
 * ```php
 * AllowedFilter::custom('role', new ScopeAllowedFilter())->default(null),
 * ```
 *
 * **Importante:** Sempre utilize `default(null)` ao declarar este filtro,
 * para que o método `allowed()` seja chamado **mesmo quando o filtro não estiver presente**
 * na requisição. Isso garante a aplicação das regras de permissão.
 *
 *
 * A model associada ao builder deve implementar o scope:
 * ```php
 * public function scopeAllowed(Builder $query, User $user, ?string $role = null): Builder
 * ```
 *
 * Caso a model não implemente `scopeAllowed`, será lançada uma exceção.
 */
class ScopeAllowedFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $model = $query->getModel();

        if (!method_exists($model, 'scopeAllowed')) {
            $modelClass = $model::class;
            throw new RuntimeException(__(
                'query-builder.filters.scope_allowed.missing_scope',
                ['model' => $modelClass],
            ));
        }

        $query->allowed(auth()->user(), $value);
    }
}
