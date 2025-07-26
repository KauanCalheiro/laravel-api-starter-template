<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authorization Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authorization for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'unauthorized' => 'Você não está autorizado a realizar esta ação.',
    'forbidden'    => 'Você não tem permissão para acessar este recurso.',

    'role' => [
        'assign' => [
            'not_allowed' => 'Você não tem permissão para atribuir o papel ":role".',
        ],
        'remove' => [
            'strongest' => [
                'not_allowed' => 'Você não pode remover o papel mais forte ":role" de si mesmo.',
            ],
        ],
        'not_found' => 'Nenhum papel válido foi atribuído ao usuário.',
    ],
];
