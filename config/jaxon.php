<?php

use Jaxon\Di\Container;
use Lagdo\DbAdmin\Db\Config\ConfigProvider;
use Lagdo\DbAdmin\Db\DbAdminPackage;

return [
    'app' => [
        'metadata' => [
            'cache' => [
                'enabled' => true,
                'dir' => storage_path('jaxon/attributes'),
            ],
        ],
        'directories' => [],
        'packages' => [
            DbAdminPackage::class => [
                'toast' => [
                    'lib' => 'notyf',
                ],
                'provider' => function(array $options, Container $di) {
                    $reader = $di->g(ConfigProvider::class);
                    return $reader->getOptions($options);
                },
                'access' => [
                    'server' => true,
                    'system' => false,
                ],
            ],
        ],
        'ui' => [
            'template' => 'bootstrap5',
        ],
        'assets' => [
            'export' => true,
            'minify' => true,
            'uri' => '/jaxon/',
            'dir' => public_path('/jaxon/'),
        ],
        'dialogs' => [
            'default' => [
                'modal' => 'bootbox',
                'alert' => 'sweetalert',
                'confirm' => 'sweetalert',
            ],
            'lib' => [
                'use' => ['notyf'],
            ],
        ],
    ],
    'lib' => [
        'core' => [
            'language' => 'en',
            'encoding' => 'UTF-8',
            'prefix' => [
                'class' => '',
            ],
            'request' => [
                'csrf_meta' => 'csrf-token',
                'uri' => '/jaxon', // The route url
            ],
            'debug' => [
                'on' => false,
                'verbose' => false,
            ],
            'error' => [
                'handle' => false,
            ],
        ],
        'js' => [
            'lib' => [
                // 'uri' => '',
            ],
        ],
    ],
];
