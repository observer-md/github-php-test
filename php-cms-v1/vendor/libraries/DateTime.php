<?php
namespace vendor\libraries;

/**
 * Date/Time Library
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class DateTime
{
    const FORMAT_DB     = 'Y-m-d H:i:s';
    const FORMAT_ISO    = 'c';

    /**
     * Return formated date/time 
     * 
     * @param string|int    $time       Formated date/time
     * @param string        $format     Date/Time format
     * @param boolean       $strict     Generate now time if FALSE
     * 
     * @return null|string
     */
    public static function formatDateTime($time = null, $format = self::FORMAT_DB, $strict = false)
    {
        if ($strict && !$time) {
            return null;
        }

        $timestamp = $time ? strtotime($time) : time();
        return date($format, $timestamp);
    }
}