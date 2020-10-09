<?php
namespace Core\Support;

/**
 * DateTime class
 * 
 * @package     Core\Support
 * @author      GEE
 */
class DateTime
{
    const FORMAT_DBD    = 'Y-m-d';          // DB DATE fotmat
    const FORMAT_DB     = 'Y-m-d H:i:s';    // DB fotmat
    const FORMAT_DBP    = 'Y-m-d H:i:s P';  // DB fotmat with timezone
    const FORMAT_DBM    = 'Y-m-d H:i:s.v';  // DB fotmat with milliseconds
    const FORMAT_ISO    = 'c';              // ISO 8601   2004-02-12T15:19:21+00:00
    const FORMAT_RFC    = 'r';              // RFC 2822   Fri, 01 May 2020 03:36:13 +0200
    const FORMAT_UNIX   = 'U';              // Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)


    /**
     * Return formated UTC/Local datetime
     * 
     * @param mixed  $timestamp
     * @param string $format
     * @param bool   $strict
     * @param bool   $utc
     * 
     * @return null|int|string
     */
    public static function format($timestamp = null, string $format = self::FORMAT_DB, bool $strict = false, bool $utc = false)
    {
        if ($strict && !$timestamp) {
            return $timestamp;
        }

        $timestamp = self::unix($timestamp);
        if ($format == self::FORMAT_UNIX) {
            return $timestamp;
        }
        return $utc ? gmdate($format, $timestamp) : date($format, $timestamp);
    }


    /**
     * Convert datetime to unix seconds
     * 
     * @param mixed $timestamp
     * @return int
     */
    public static function unix($timestamp = null)
    {
        if (!$timestamp) {
            $timestamp = time();
        }

        if (!is_numeric($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        return $timestamp;
    }


    /**
     * Return formated UTC datetime
     * 
     * @param mixed  $timestamp
     * @param string $format
     * @param bool   $strict
     * 
     * @return null|int|string
     */
    public static function utc($timestamp = null, string $format = self::FORMAT_DB, bool $strict = false)
    {
        return self::format($timestamp, $format, $strict, true);
    }


    /**
     * Return formated local datetime
     * 
     * @param mixed  $timestamp
     * @param string $format
     * @param bool   $strict
     * 
     * @return null|int|string
     */
    public static function local($timestamp = null, string $format = self::FORMAT_DB, bool $strict = false)
    {
        return self::format($timestamp, $format, $strict, false);
    }


    /**
     * Validate date-time by format
     * 
     * @param string $timestamp
     * @param string $format
     * 
     * @return bool
     */
    public static function validate(string $timestamp, string $format = self::FORMAT_DB)
    {
        return !!\DateTime::createFromFormat($format, $timestamp);
    }
}
