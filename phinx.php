<?php

return
[
    'paths' => [
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',

        'development' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'name' => 'desafio-doity',
            'user' => 'doity',
            'pass' => 'doity',
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
