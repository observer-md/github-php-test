<?php
namespace vendor\core\cache;


/**
 * Memory Cache class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class MemoryCache implements Cache
{
    /**
     * Stored data
     * @var array
     */
    private static $data = [];

    /**
     * Establish cache connection
     */
    public function connect()
    {
        // connect
    }

    /**
     * Return data from cache
     * 
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return self::$data[$key] ?? null;
    }

    /**
     * Store data in cache
     * 
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl
     */
    public function set(string $key, $value, $ttl = 0)
    {
        self::$data[$key] = $value;
    }
}
