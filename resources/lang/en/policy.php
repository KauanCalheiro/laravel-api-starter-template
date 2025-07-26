<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Policy Authorization Language Lines
    |--------------------------------------------------------------------------
    |
    | The messages below are used to display standardized access responses
    | in the application's policies. They support action interpolation.
    |
    */

    'responses' => [
        'deny' => [
            'view'   => 'You do not have permission to view the :model.',
            'create' => 'You do not have permission to create a :model.',
            'update' => 'You do not have permission to update the :model.',
            'delete' => 'You do not have permission to delete the :model.',
        ],
    ],
];
