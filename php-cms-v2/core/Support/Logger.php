<?php
namespace Core\Support;


/**
 * Logger class
 * 
 * @package     Core\Support
 * @author      GEE
 */
class Logger
{
    const TYPE_INFO     = 'INFO';
    const TYPE_NOTICE   = 'NOTICE';
    const TYPE_WARNING  = 'WARNING';
    const TYPE_ERROR    = 'ERROR';
    const TYPE_DEBUG    = 'DEBUG';

    /**
     * 
     */
    protected $dayly    = false;
    protected $file     = null;


    /**
     * Log message
     * 
     * @param string $message
     * @param string $type
     * @return string
     */
    public function log($message, $type)
    {
        $date = DateTime::local(null, DateTime::FORMAT_DBP);
        $data = "[{$date}][{$type}] {$message}";
        var_dump($data);

        return $data;
    }


    /**
     * Log INFO
     * 
     * @param string $message
     * @return string
     */
    public function info($message)
    {
        return $this->log($message, self::TYPE_INFO);
    }


    /**
     * Log NOTICE
     * 
     * @param string $message
     * @return string
     */
    public function notice($message)
    {
        return $this->log($message, self::TYPE_NOTICE);
    }


    /**
     * Log WARNING
     * 
     * @param string $message
     * @return string
     */
    public function warning($message)
    {
        return $this->log($message, self::TYPE_WARNING);
    }


    /**
     * Log ERROR
     * 
     * @param string $message
     * @return string
     */
    public function error($message)
    {
        return $this->log($message, self::TYPE_ERROR);
    }


    /**
     * Log DEBUG
     * 
     * @param string $message
     * @return string
     */
    public function debug($message)
    {
        return $this->log($message, self::TYPE_DEBUG);
    }
}
