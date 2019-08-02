<?php

return [
    /**
     * DB
     */
    'database' => [
        'host'      => '127.0.0.1',
        'port'      => 3306,
        'user'      => 'xxx-user',
        'pass'      => 'xxx-pass',
        'dbname'    => 'color_test',
        'charset'   => 'utf8',
    ],


    /**
     * Routes
     */
    'routes' => [
        'home/index' => [
            'controller' => 'HomeController',
            'action'     => 'actionIndex',
        ],

        'api/get-votes' => [
            'controller' => 'ApiController',
            'action'     => 'actionGetVotes',
        ],
    ],
];