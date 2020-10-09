<?php
namespace Core\Support;

/**
 * Random class
 * 
 * @package Core\Support
 * @author  GEE
 */
class Random
{
    /**
     * Generates cryptographically secure pseudo-random integers
     * 
     * @param int $min      The lowest value to be returned, which must be PHP_INT_MIN or higher.
     * @param int $max      The highest value to be returned, which must be less than or equal to PHP_INT_MAX
     * @return int
     */
    public static function number(int $min = null, int $max = null)
    {
        $min = $min ?? PHP_INT_MIN;
        $max = $max ?? PHP_INT_MAX;

        return random_int($min, $max);
    }


    /**
     * Generates cryptographically secure pseudo-random bytes
     * 
     * @param int $length       The length of the random string that should be returned in bytes
     * @return string
     */
    public static function bytes(int $length) : string
    {
        return random_bytes($length);
    }
}
