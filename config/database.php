<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env(strtoupper('DB_' . env('DB_SYSTEM') . '_DATABASE'), 'default_db'),  // Usará DB_DEMETER_DATABASE si DB_SYSTEM=demeter
            'username' => env(strtoupper('DB_' . env('DB_SYSTEM') . '_USERNAME'), 'root'),
            'password' => env(strtoupper('DB_' . env('DB_SYSTEM') . '_PASSWORD'), ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ],
];
