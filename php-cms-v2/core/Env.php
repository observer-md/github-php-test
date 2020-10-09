<?php
namespace Core;


/**
 * Env class
 * Load Env config data from INI file
 * 
 * @package     Core
 * @author      GEE
 */
class Env
{
    /**
     * Env config data
     * @var array
     */
    private static $data = [];


    /**
     * Load env config data
     */
    private static function load()
    {
        if (!self::$data) {
            $path = dirname(__DIR__) . '/config.ini';
            if (file_exists($path)) {
                self::$data = parse_ini_file($path, false, INI_SCANNER_TYPED);
            } else {
                self::$data = ['error' => "ENV file [{$path}] does not exists."];
            }
        }
    }


    /**
     * Return env config variable
     * 
     * @param string $name      Key name
     * @param mixed  $default   Default key value
     * @return mixed
     */
    public static function get(string $name, $default = null)
    {
        self::load();
        if (!$name) {
            return self::$data;
        }
        return self::$data[$name] ?? $default;
    }
}
