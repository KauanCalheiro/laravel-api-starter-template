<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de linguagem para filtros personalizados do Query Builder
    |--------------------------------------------------------------------------
    |
    | As mensagens abaixo são usadas para exibir erros e mensagens relacionadas
    | a filtros customizados utilizados com o Spatie Query Builder.
    | São compatíveis com interpolação de variáveis.
    |
    */

    'filters' => [
        'scope_allowed' => [
            'missing_scope' => 'A model [:model] deve implementar o escopo "allowed" para usar o filtro ScopeAllowedFilter.',
        ],
    ],

    'includes' => [
        'role_list' => [
            'invalid' => 'A relação ":relation" não é válida para o RoleListInclude. Por favor, use "roles".',
        ],
    ],
];
