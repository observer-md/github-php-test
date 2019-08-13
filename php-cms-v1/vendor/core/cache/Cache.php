<?php
namespace vendor\core\cache;

/**
 * Cache interface
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
interface Cache
{
    /**
     * Establish cache connection
     */
    public function connect();

    /**
     * Return data from cache
     * 
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Store data in cache
     * 
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl
     */
    public function set($key, $value, $ttl = 0);
}
