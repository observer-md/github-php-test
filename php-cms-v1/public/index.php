<?php

define('DIR_BASE', dirname(__DIR__));
define('DIR_APP', DIR_BASE . '/app');

// Autoload
require(DIR_BASE . '/vendor/core/Autoload.php');

// config
$config = require(DIR_APP . '/config/main.php');

(new vendor\core\Application())->run($config);
