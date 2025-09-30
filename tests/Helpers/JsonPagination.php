<?php

namespace Tests\Helpers;

class JsonPagination
{
    public const STRUCTURE = [
        'data',
        'links' => [
            'first',
            'last',
            'prev',
            'next',
        ],
        'meta' => [
            'current_page',
            'from',
            'last_page',
            'links',
            'path',
            'per_page',
            'to',
            'total',
        ],
    ];
}
