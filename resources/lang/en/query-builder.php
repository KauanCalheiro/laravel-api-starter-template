<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Language lines for custom Query Builder filters
    |--------------------------------------------------------------------------
    |
    | The lines below are used to display error messages and other
    | notifications related to custom filters used with the Spatie Query Builder.
    | These messages support variable interpolation.
    |
    */

    'filters' => [
        'scope_allowed' => [
            'missing_scope' => 'The model [:model] must implement the "allowed" scope to use the ScopeAllowedFilter.',
        ],
    ],

    'includes' => [
        'role_list' => [
            'invalid' => 'The relation ":relation" is not valid for the RoleListInclude. Please use "roles".',
        ],
    ],
];
