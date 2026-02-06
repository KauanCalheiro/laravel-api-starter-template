<?php

use App\Services\Auth\JwtAuthService;

return [
    'handlers' => [
        'jwt' => JwtAuthService::class,
    ],
];
