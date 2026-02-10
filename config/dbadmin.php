<?php

return [
    'common' => [
        'access' => [
            'server' => true,
            'system' => false,
        ],
    ],
    'fallback' => [
    ],
    'users' => [
    ],
    'audit' => [
        'options' => [
            'library' => [
                'enabled' => false,
            ],
            'enduser' => [
                'enabled' => false,
            ],
            'history' => [
                'enabled' => false,
                'distinct' => true,
                'limit' => 15,
            ],
            'favorite' => [
                'enabled' => false,
                'limit' => 10,
            ],
        ],
        'database' => [
            // Same as the "servers" items, but "name" is the database name.
        ],
        'allowed' => [
            // The emails of users that are allowed to access the audit page.
        ],
    ],
];
