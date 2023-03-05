<?php

return [
    'settings' => [
        'name' => $_ENV['APP_NAME'] ?? 'App name',
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => $_ENV['APP_DEBUG'] ?? false,
        // Database
        'db' => [
            'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'database' => $_ENV['DB_DATABASE'] ?? 'database',
            'username' => $_ENV['DB_USERNAME'] ?? 'user',
            'password' =>  $_ENV['DB_PASSWORD'] ?? 'password',
            'charset'   => $_ENV['DB_CHARSET'] ?? 'utf8',
            'collation' =>  $_ENV['DB_COLLATION'] ?? 'utf8_unicode_ci',
            'prefix'    => $_ENV['DB_PREFIX'] ?? '',
        ]
    ],
];
