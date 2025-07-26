<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de linguagem para políticas de autorização
    |--------------------------------------------------------------------------
    |
    | As mensagens abaixo são usadas para exibir respostas padronizadas de
    | acesso nas policies da aplicação. São compatíveis com interpolação.
    |
    */

    'responses' => [
        'deny' => [
            'view'   => 'Você não tem autorização para visualizar :model.',
            'create' => 'Você não tem autorização para criar :model.',
            'update' => 'Você não tem autorização para editar :model.',
            'delete' => 'Você não tem autorização para excluir :model.',
        ],
    ],
];
