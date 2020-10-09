<?php
use \Core\Env;

/**
 * Web configuration file
 */
return [
    // APP
    'name'      => Env::get('APP_NAME', 'Test Application'),
    'env'       => Env::get('APP_ENV', 'production'),//development, production
    'debug'     => Env::get('APP_DEBUG', false),
    'key'       => Env::get('APP_KEY'),
    'cipher'    => 'AES-256-CBC',
    'timezone'  => 'UTC',

    // DB
    'database' => [
        'name'      => 'default',
        'driver'    => Env::get('DB_DRIVER', 'mysql'),
        'host'      => Env::get('DB_HOST', 'localhost'),
        'port'      => Env::get('DB_PORT', 3306),
        'username'  => Env::get('DB_USERNAME'),
        'password'  => Env::get('DB_PASSWORD'),
        'dbname'    => Env::get('DB_DATABASE'),
        'charset'   => 'utf8',
    ],
    
    // 'database' => [
    //     'master' => [],
    //     'slave'  => [],
    // ],

    // Cache
    'cache' => [],
    
    // Session
    'session' => [],

    
    
    
];
