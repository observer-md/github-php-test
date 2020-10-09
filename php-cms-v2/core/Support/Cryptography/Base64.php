<?php
namespace Core\Support\Cryptography;

/**
 * MIME Base64 class
 * 
 * @package Core\Support\Secure
 * @author  GEE
 */
class Base64
{
    /**
     * Encodes data with MIME base64
     * 
     * @param string $data      The data to encode
     * @return string
     */
    public static function encode(string $data) : string
    {
        return base64_encode($data);
    }


    /**
     * Decodes data encoded with MIME base64
     * 
     * @param string $data      The encoded data
     * @param bool   $strict    TRUE then function will return FALSE if the input contains character from outside the base64 alphabet
     * 
     * @return string
     */
    public static function decode(string $data, bool $strict = false) : string
    {
        return base64_decode($data, $strict);
    }



    /**
     * Encodes data with MIME base64 URL
     * 
     * This function supports "base64url" as described in Section 5 of RFC 4648,
     * "Base 64 Encoding with URL and Filename Safe Alphabet".
     * 
     * @param string $data      The data to encode
     * @param bool   $pad       TRUE - Keep pad '=' on the end of the string
     * 
     * @return string
     */
    public static function encodeUrl(string $data, bool $pad = false) : string
    {
        $data = str_replace(['+','/'], ['-','_'], base64_encode($data));
        return !$pad ? rtrim($data, '=') : $data;
    }
    

    /**
     * Decodes data encoded with MIME base64 URL
     * 
     * This function supports "base64url" as described in Section 5 of RFC 4648,
     * "Base 64 Encoding with URL and Filename Safe Alphabet".
     * 
     * 
     * @param string $data      The encoded data
     * @param bool   $strict    TRUE then function will return FALSE if the input contains character from outside the base64 alphabet
     * 
     * @return string
     */
    public static function decodeUrl(string $data, bool $strict = false) : string
    {
        $data = str_replace(['-','_'], ['+','/'], $data);
        return base64_decode($data, $strict);
    }
}
