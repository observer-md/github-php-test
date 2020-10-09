<?php
namespace Core\Cache;


/**
 * Base Cache class
 * 
 * @abstract
 * @package     Core\Cache
 * @author      ME
 */
abstract class BaseCache
{
    /**
     * Connection
     * @var object
     */
    protected static $conn;

    /**
     * Default cache TTL
     * @var int
     */
    protected static $timestamp = 86400;


    /**
     * Get cache
     * 
     * @param mixed $key    Cache key
     * @return mixed
     */
    abstract public function get($key);


    /**
     * Set cache
     * 
     * @param mixed $key    Cache key
     * @param mixed $value  Cache value
     * @param mixed $ttl    Cache TTL
     */
    abstract public function set($key, $value, $ttl = null);


    /**
     * Get/Set cache TTL (Time of live)
     * 
     * @param mixed $key    Cache key
     * @param int   $ttl    Cache TTL in seconds
     * 
     * @return int
     */
    abstract public function ttl($key, $ttl = null);


    /**
     * Check if key exists
     * 
     * @param mixed $key    Cache key
     * @return bool
     */
    abstract public function exists($key);


    /**
     * Delete keys
     * 
     * @param mixed $key    Cache key
     */
    abstract public function delete($key);


    /**
     * Delete all keys
     */
    abstract public function flush();


    /**
     * 
     */
    public static function init()
    {
        return new static();
    }

    
    /**
     * Generate key
     * 
     * @param mixed $key    Cache key
     * @return string
     */
    public static function genKey($key)
    {
        $key = !is_array($key) ? [$key] : $key;
        return join(':', $key);
    }


    /**
     * Generate cache timestamp
     * 
     * @param int $value      Cache timestamp in sec
     * @return array<int>
     */
    protected static function genTimestamp($ttl = 0)
    {
        $ttl = (int) $ttl;
        $ttl = $ttl ?: self::$timestamp;

        $current = time();
        return [
            'current' => $current,
            'ttl'     => $current + $ttl,
        ];
    }


    /**
     * Encrypt cache value
     * 
     * @param mixed $value      Cache value
     * @return string
     */
    protected static function valueEnc($value)
    {
        return serialize($value);
    }


    /**
     * Decrypt cache value
     * 
     * @param string $value     Serialize cache value
     * @return mixed
     */
    protected static function valueDec($value)
    {
        return unserialize($value);
    }
}
