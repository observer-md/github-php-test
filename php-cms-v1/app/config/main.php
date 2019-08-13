<?php

return [
    // Version
    'verions'  => '1.0.0',

    // Set site timezone
    'timezone' => 'America/New_York', // Europe/Berlin

    // Default controller
    'defaultController' => 'site', // 'admin/auth'

    // Default  action
    'defaultAction' => 'index',

    // DB
    'database'  => [
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => 'user',
        'pass'      => 'pass',
        'dbname'    => 'db-name',
        'charset'   => 'utf8'
    ],

    // Session
    'session'   => [
        'user' => 'session-auth-user',
    ],

    // Cookie
    'cookie'    => [
        'secret' => 'secret-67a74306b06d0c01624fe0d0249a570f4d093747',
    ],

    // API
    'api'       => [
        'clientKey'    => 'client-key-0144712dd81be0c3d9724f5e56ce6685',
        'clientSecret' => 'client-secret-07e23ede2756aa3f5f7cc9759117c4910875e032c27b8556a1e20626224f10ec',
    ]
];
