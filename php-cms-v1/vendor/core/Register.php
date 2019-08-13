<?php
namespace vendor\core;

/**
 * Register class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Register
{
    /**
     * Register Instance
     * @var \Register $instance
     */
    private static $instance;

    /**
     * Register data
     * @var array
     */
    private $data = [];


    /**
     * 
     */
    private function __construct()
    {
        $this->data = [];
    }
    

    /**
     * Init
     */
    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Set data
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }


    /**
     * Return data by key
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->data)) {
            return null;
        }
        return $this->data[$key];
    }


    /**
     * Return all data
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }


    /**
     * Set request
     * 
     * @param string    $key        Request name
     * @param array     $config     Config data
     */
    public function setConfig(array $config)
    {
        $this->data['config'] = $config;
    }


    /**
     * Return request
     * 
     * @param string $key   Request name
     * @return Request
     */
    public function getConfig($key)
    {
        return $this->data['config'][$key];
    }


    /**
     * Set request
     * 
     * @param string    $key        Request name
     * @param Request   $request    Request object
     */
    public function setRequest($key, Request $request)
    {
        $this->data['requests'][$key] = $request;
    }


    /**
     * Return request
     * 
     * @param string $key   Request name
     * @return Request
     */
    public function getRequest($key)
    {
        return $this->data['requests'][$key];
    }


    /**
     * Return DB
     * 
     * @return DB
     */
    public function getDB()
    {
        return $this->data['database']; 
    }
}