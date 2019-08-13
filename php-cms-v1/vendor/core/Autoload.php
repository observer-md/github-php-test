<?php
namespace vendor\core;

/**
 * Class Autoload
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Autoload
{
    /**
     * Init
     */
    public static function init()
    {
        spl_autoload_register(['vendor\core\Autoload', 'load']);
    }

    /**
     * Load class
     * @param string $className     Class name
     */
    private static function load($className)
    {
        $path = DIR_BASE . DIRECTORY_SEPARATOR . $className . '.php';
        require_once($path);
    }
}

// init
Autoload::init();