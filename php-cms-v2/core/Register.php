<?php
namespace Core;

/**
 * Description of Register
 *
 * @package     Core
 * @author      GEE
 */
class Register
{
    /**
     * List of parameters
     * @var array
     */
    private static $data = [];
    

    /**
     * 
     * @param array $params
     */
    public static function getAll()
    {
        return self::$data;
    }
    

    /**
     * Set parameter
     * 
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        self::$data[$key] = $value;
    }
    

    /**
     * Get parameter
     * 
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$data)) {
            return null;
        }
        
        return self::$data[$key];
    }
    

    /**
     * Get config
     * 
     * @param string $key   Request name
     * @return Request
     */
    public static function config($key)
    {
        return self::$data['config'][$key] ?? null;
    }

    
    /**
     * Return DB
     * 
     * @return DB
     */
    public static function db()
    {
        return self::$data['database'] ?? []; 
    }
}
