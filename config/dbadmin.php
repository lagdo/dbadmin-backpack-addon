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
    'queries' => [
        'record' => [
            'builder' => [
                'enabled' => false,
            ],
            'editor' => [
                'enabled' => false,
            ],
        ],
        'admin' => [
            'history' => [
                'show' => false,
                'distinct' => true,
                'limit' => 15,
            ],
            'favorite' => [
                'show' => false,
                'limit' => 10,
            ],
            'preferences' => [
                'enabled' => false,
            ],
        ],
        'audit' => [
            'enabled' => true,
            'users' => [
                // The emails of users that are allowed to access the audit page.
            ],
        ],
        'database' => [
            // Same as the "servers" items, but "name" is the database name.
        ],
    ],
];
