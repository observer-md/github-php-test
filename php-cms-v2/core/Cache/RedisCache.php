<?php
namespace Core\Cache;


/**
 * Redis Cache class
 * 
 * @link https://github.com/nrk/predis
 * @link https://github.com/phpredis/phpredis
 * @link https://redislabs.com/lp/php-redis/
 * @link https://pecl.php.net/package/redis
 * @link https://www.tutorialspoint.com/redis/redis_php.htm
 * 
 * @package     Core\Cache
 * @author      ME
 */
class RedisCache extends BaseCache
{
    /**
     * Get cache
     * 
     * @param mixed $key    Cache key
     * @return mixed
     */
    public function get($key)
    {
        return null;
    }


    /**
     * Set cache
     * 
     * @param mixed $key    Cache key
     * @param mixed $value  Cache value
     * @param mixed $ttl    Cache TTL
     */
    public function set($key, $value, $ttl = null)
    {
        return null;
    }


    /**
     * Get/Set cache TTL (Time of live)
     * 
     * @param mixed $key    Cache key
     * @param int   $ttl    Cache TTL in seconds
     * 
     * @return int
     */
    public function ttl($key, $ttl = null)
    {
        return null;
    }


    /**
     * Check if key exists
     * 
     * @param mixed $key    Cache key
     * @return bool
     */
    public function exists($key)
    {
        return false;
    }


    /**
     * Delete keys
     * 
     * @param mixed $key    Cache key
     */
    public function delete($key)
    {
        
    }


    /**
     * Delete all keys
     */
    public function flush()
    {
        
    }


    /**
     * Select DB
     * 
     * @param int $db   Database name
     * @return RedisCache
     */
    public function select($db = 0)
    {
        return $this;
    }
}
