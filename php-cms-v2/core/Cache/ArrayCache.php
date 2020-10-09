<?php
namespace Core\Cache;


/**
 * Array Cache class
 * 
 * @package     Core\Cache
 * @author      ME
 */
class ArrayCache extends BaseCache
{
    /**
     * Cache data
     * @var array
     */
    public static $data = [
        // ['value' => null, 'time' => 0]
    ];


    /**
     * Get cache
     * 
     * @param mixed $key    Cache key
     * @return mixed
     */
    public function get($key)
    {
        if (!$data = $this->check($key)) {
            return null;
        }
        return self::valueDec($data['value']);
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
        $key = self::genKey($key);
        self::$data[$key] = [
            'value' => self::valueEnc($value),
            'time'  => self::genTimestamp($ttl)['ttl'],
        ];
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
        if (!$data = $this->check($key)) {
            return null;
        }

        $time = self::genTimestamp($ttl);
        
        // set timestamp
        if ($ttl) {
            self::$data[$key]['time'] = $time['ttl'];
        }

        return $data['time'] - $time['current'];
    }


    /**
     * Check if key exists
     * 
     * @param mixed $key    Cache key
     * @return bool
     */
    public function exists($key)
    {
        return !!$this->get($key);
    }


    /**
     * Delete keys
     * 
     * @param mixed $key    Cache key
     */
    public function delete($key)
    {
        $key = self::genKey($key);
        if (isset(self::$data[$key])) {
            unset(self::$data[$key]);
        }
    }


    /**
     * Delete all keys
     */
    public function flush()
    {
        self::$data = [];
    }

    
    /**
     * Check/Return cache
     * 
     * @param mixed $key
     * @return array
     */
    private function check($key)
    {
        $key = self::genKey($key);
        if (!isset(self::$data[$key])) {
            return null;
        }

        if (self::$data[$key]['time'] < time()) {
            $this->delete($key);
            return null;
        }

        return self::$data[$key];
    }
}
