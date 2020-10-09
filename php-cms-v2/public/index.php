<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/app/Config/routes.php';

$config = require_once dirname(__DIR__) . '/app/Config/web.php';
(new \Core\Application())->run($config);
