<?php

define("DIR_BASE", dirname(__DIR__));
define("DIR_APP", DIR_BASE . "/app");

// load config
$config = require(DIR_APP . "/config.php");

/**
 * check route
 */
$controller = trim($_GET['c'] ?? 'home');
$action     = trim($_GET['a'] ?? 'index');

$route = $controller . '/' . $action;
$routeData = $config['routes'][$route] ?? null; 
if (!$routeData) {
    include (DIR_APP . "/views/404.php");
    exit;
}


/*
 * set DB connection
 */
require_once (DIR_APP . '/libs/DB.php');
DB::init($config['database']);


/**
 * load controller
 */
require DIR_APP . "/controllers/" . $routeData['controller'] . ".php";

$controllerObj = new $routeData['controller']();
$controllerObj->{$routeData['action']}();
