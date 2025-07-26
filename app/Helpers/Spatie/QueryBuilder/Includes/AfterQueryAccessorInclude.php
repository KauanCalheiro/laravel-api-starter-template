<?php

namespace App\Helpers\Spatie\QueryBuilder\Includes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\Includes\IncludeInterface;

/**
 * Include customizado para Spatie\QueryBuilder que acessa dinamicamente
 * um accessor virtual após a execução da query principal.
 *
 * ⚙️ Como funciona:
 * - Ao usar `AllowedInclude::custom('alunos', new AfterQueryAccessorInclude())`,
 *   esta classe será invocada com `$relation = 'alunos'`.
 * - Após a execução da query (`afterQuery()`), o accessor `getAlunosAttribute()`
 *   será chamado automaticamente para cada item retornado.
 *
 * - Suporta tanto acessores simples (`getAlunosAttribute()`) quanto acessores nidificados,
 *   como alunos.curso (`getCursoAttribute()` em cada aluno). Limitada a um nível de profundidade,
 *   portanto, não suporta múltiplos níveis como `alunos.curso.professor`.
 * - Se o accessor não existir, ele simplesmente carrega a relação normalmente.
 *
 *
 * ✅ Útil para relações complexas, computed properties ou dados de múltiplos bancos,
 *    onde não se pode declarar uma relação real no Eloquent.
 *
 * 💡 Exemplo de uso:
 * ```php
 * QueryBuilder::for(PlanejamentoAcao::class)
 *     ->allowedIncludes([
 *         AllowedInclude::custom('alunos', new AfterQueryAccessorInclude()),
 *     ])
 *     ->get();
 * ```
 */
class AfterQueryAccessorInclude implements IncludeInterface
{
    public function __invoke(Builder $query, string $include)
    {
        if (substr_count($include, '.') > 1) {
            return;
        }

        $query->afterQuery(function (Collection $results) use ($include) {
            if (strpos($include, '.') === false) {
                foreach ($results as $model) {
                    $this->handleSingleInclude($model, $include);
                }
            } else {
                [$relation, $attribute] = explode('.', $include, 2);
                foreach ($results as $model) {
                    $this->handleNestedInclude($model, $relation, $attribute);
                }
            }
        });
    }

    protected function handleSingleInclude(Model $model, string $relation): void
    {
        $accessor = 'get' . ucfirst($relation) . 'Attribute';

        if (method_exists($model, $accessor)) {
            $value = $model->$relation;

            $model->setRelation($relation, $value);
        } else {
            $model->loadMissing($relation);
        }
    }

    protected function handleNestedInclude(Model $model, string $relation, string $attribute): void
    {
        $model->loadMissing($relation);

        $children = $model->getRelation($relation);
        $items    = $children instanceof Collection
                ? $children
                : collect([$children]);

        $items->each(function (Model $child) use ($attribute) {
            $child->$attribute;

            $child->append($attribute);
        });

        $model->setRelation(
            $relation,
            $children instanceof Collection ? $items : $items->first(),
        );
    }
}
