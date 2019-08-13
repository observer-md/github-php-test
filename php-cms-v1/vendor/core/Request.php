<?php
namespace vendor\core;

/**
 * Request class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Request
{
    /**
     * Request name
     */
    private $name;

    /**
     * Parameters
     * @var array
     */
    private $data = [];

    /**
     * 
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set parameter
     * 
     * @param string $key       Parameter name
     * @param mixed  $value     Parameter value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Return parameter value
     * 
     * @param string $key   Parameter name
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->data += self::getData();

        // if (!array_key_exists($key, $this->data)) {
        //     return $default;
        // }
        if (empty($this->data[$key])) {
            return $default;
        }
        return $this->data[$key];
    }

    /**
     * Return all parameters
     * 
     * @return array
     */
    public function getAll()
    {
        $this->data += self::getData();
        return $this->data;
    }


    /**
     * Return global data
     * @return array
     */
    private function getData()
    {
        switch ($this->name) {
            case 'GET':
                return $_GET ?? [];

            case 'POST':
                return $_POST ?? [];
            
            case 'REQUEST':
                return $_REQUEST ?? [];

            case 'SERVER':
                return $_SERVER ?? [];
            
            case 'COOKIE':
                return $_COOKIE ?? [];
            
            case 'SESSION':
                return $_SESSION ?? [];

            default:
                return [];
        }
    }
}