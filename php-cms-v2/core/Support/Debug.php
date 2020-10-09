<?php
namespace Core\Support;


/**
 * Debug class
 * 
 * @package Core\Support
 * @author  GEE
 */
class Debug
{
    /**
     * Debug data
     * @var array
     */
    public static $data = [
        /*
        'debug-name' => [
            't1' => 0, // time start
            't2' => 0, // time stop
            'm1' => 0, // memory start
            'm2' => 0, // memory stop
        ]
        */
    ];


    /**
     * Debug Start
     * 
     * @param string $name  Data set name
     */
    public static function start(string $name = 'default')
    {
        self::$data[$name] = [
            't1' => microtime(true),
            't2' => 0,
            'm1' => memory_get_usage(false),
            'm2' => 0,
        ];
    }


    /**
     * Debug Stop and return debug data
     * 
     * @param string $name  Data set name
     * @return array
     */
    public static function stop(string $name = 'default')
    {
        if (!isset(self::$data[$name])) {
            self::$data[$name] = [];
        }

        self::$data[$name]['t2'] = microtime(true);
        self::$data[$name]['m2'] = memory_get_usage(false);

        return self::data($name);
    }


    /**
     * Return debug data
     * 
     * @param string $name  Data set name
     * @return array
     */
    public static function data(string $name = 'default')
    {
        if (!isset(self::$data[$name])) {
            self::$data[$name] = [];
        }

        self::$data[$name] += [
            't1' => 0,
            't2' => 0,
            'm1' => 0,
            'm2' => 0,
        ];

        return [
            'time'   => (self::$data[$name]['t2'] - self::$data[$name]['t1']),
            'memory' => (self::$data[$name]['m2'] - self::$data[$name]['m1']),
        ];
    }


    public static function convertSize($size)
    {
        $unit = ['B','KB','MB','GB','TB','PB'];
        return @round($size / pow(1024, ($i = floor(log($size,1024)))), 2) . ' ' . $unit[$i];
    }
}
