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

    'unauthorized' => 'You are not authorized to perform this action.',
    'forbidden'    => 'You do not have permission to access this resource.',

    'role' => [
        'assign' => [
            'not_allowed' => 'You do not have permission to assign the ":role" role.',
        ],
        'remove' => [
            'strongest' => [
                'not_allowed' => 'You cannot remove the strongest ":role" role from yourself.',
            ],
        ],
        'not_found' => 'No valid roles have been assigned to the user.',
    ],
];
