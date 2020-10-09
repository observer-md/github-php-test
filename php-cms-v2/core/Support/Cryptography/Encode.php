<?php
namespace Core\Support\Cryptography;

/**
 * Encode class
 * 
 * @package Core\Support\Secure
 * @author  GEE
 */
class Encode
{
    /**
     * Convert binary data into hexadecimal representation
     * 
     * @param string $str   A string
     * @return string
     */
    public static function hex(string $str) : string
    {
        return bin2hex($str);
    }


    /**
     * Decodes a hexadecimally encoded binary string
     * 
     * @param string $str   Hexadecimal representation of data
     * @return string
     */
    public static function bin(string $str) : string
    {
        return hex2bin($str);
    }
}
