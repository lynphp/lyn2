<?php

/**
 * Lyn Supports Database load balancing and horizontal scalling by default.
 * Using multiple query connections available,
 * Lyn is able to perform round-robin query executions.
 * 
 */

return [
    'mysql' => [
        'query' => [
            0 => [
                'username' => 'root',
                'password' => '',
                'hostname' => 'localhost',
                'schema' => 'lyn-dev',
                'port' => 3308,
                'cache' => [
                    'path' => ''
                ]
            ],
            1 => [
                'username' => 'root',
                'password' => '',
                'hostname' => 'localhost',
                'schema' => 'lyn-dev',
                'port' => 3308,
            ],
            2 => [
                'username' => 'root',
                'password' => '',
                'hostname' => 'localhost',
                'schema' => 'lyn-dev',
                'port' => 3308,
            ]
        ],
        'execute' => [
            0 => [
                'username' => 'root',
                'password' => '',
                'hostname' => 'localhost',
                'schema' => 'lyn-dev',
                'port' => 3308,
            ]
        ]
    ]
];
