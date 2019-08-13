<?php
namespace vendor\core;

/**
 * DB connection
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class DB
{
    private static $instance;
    private $db;
    private $config;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    public static function init(array $config = [])
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    private function connect()
    {   
        // build DSN
        $dsn = 'mysql:dbname='. $this->config['dbname']
            . ';host=' . $this->config['host']
            . ';port=' . $this->config['port']
            . ';charset=' . $this->config['charset'];
        
        // DB options
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];

        // set CHARSET for old PHP: < PHP 5.3.6
        if( version_compare(PHP_VERSION, '5.3.6', '<') ){
            if( defined('\PDO::MYSQL_ATTR_INIT_COMMAND') ){
                $options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $this->config['charset'];
            }
        }

        $this->db = new \PDO($dsn, $this->config['user'], $this->config['pass'], $options);
    }

    public function getConn()
    {
        if (!$this->db) {
            $this->connect();
        }
        
        return $this->db;
    }
}