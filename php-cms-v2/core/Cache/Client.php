<?php
namespace Core\Cache;


/**
 * Database Cache class
 * 
 * @package     Core\Cache
 * @author      ME
 */
class Client
{
    /**
     * Cache instance
     * @var BaseCache
     */
    protected static $instance = [];
    

    /**
     * Return cache instance
     * 
     * @param string $name  Cache driver name
     * @return BaseCache
     */
    public static function init($driver = 'array')
    {
        if (!isset(self::$instance[$driver])) {
            self::$instance[$driver] = new ArrayCache();
        }
        return self::$instance[$driver];
    }
}
