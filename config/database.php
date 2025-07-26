<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'extensao'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [
        'sqlite' => [
            'driver'                  => 'sqlite',
            'url'                     => env('DB_URL'),
            'database'                => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix'                  => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout'            => null,
            'journal_mode'            => null,
            'synchronous'             => null,
        ],

        'extensao' => [
            'driver'         => 'pgsql',
            'url'            => env('DB_URL'),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '5432'),
            'database'       => env('DB_DATABASE', 'laravel'),
            'username'       => env('DB_USERNAME', 'root'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => env('DB_CHARSET', 'utf8'),
            'prefix'         => '',
            'prefix_indexes' => true,
            'search_path'    => 'public',
            'sslmode'        => 'prefer',
        ],

        'academico' => [
            'driver'         => 'pgsql',
            'url'            => '',
            'host'           => env('ACADEMICO_DB_HOST', ''),
            'port'           => env('ACADEMICO_DB_PORT', ''),
            'database'       => env('ACADEMICO_DB_DATABASE', ''),
            'username'       => env('ACADEMICO_DB_USERNAME', ''),
            'password'       => env('ACADEMICO_DB_PASSWORD', ''),
            'charset'        => env('ACADEMICO_DB_CHARSET', ''),
            'prefix'         => '',
            'prefix_indexes' => true,
            'search_path'    => 'public',
            'sslmode'        => 'prefer',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table'                  => 'migrations',
        'update_date_on_publish' => true,
    ],
];
