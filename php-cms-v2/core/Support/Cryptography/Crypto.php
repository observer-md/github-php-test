<?php
namespace Core\Support\Cryptography;

/**
 * Cipher class
 * 
 * @package Core\Support\Secure
 * @author  GEE
 */
class Crypto
{
    /**
     * The cipher method
     */
    const AES128CBC = 'AES-128-CBC';
    const AES256CBC = 'AES-256-CBC';
    const AES128CTR = 'AES-128-CTR';
    const AES256CTR = 'AES-256-CTR';
    const AES128GCM = 'AES-128-GCM';
    const AES256GCM = 'AES-256-GCM';
    

    /**
     * Generates cryptographically secure pseudo-random bytes
     * 
     * @param int $length
     * @return string
     */
    public static function randomBytes(int $length) : string
    {
        return random_bytes($length);
    }


    /**
     * Generates cryptographically secure pseudo-random integers
     * 
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function randomInt(int $min, int $max) : int
    {
        return random_int($min, $max);
    }


    /**
     * Computes a digest
     * 
     * Computes a digest hash value for the given data using a given method,
     * and returns a raw or binhex encoded string
     * 
     * @param string $data      The data
     * @param bool   $raw       Setting to TRUE will return as raw output data, otherwise the return value is binhex encoded
     * @return string
     */
    public static function digest(string $data, bool $raw = false) : string
    {
        return openssl_digest($data, 'sha256', $raw);
    }


    /**
     * Generate signature
     * 
     * @param string $data      The data
     * @param string $key       The key
     * @param bool   $raw       Setting to TRUE will return as raw output data, otherwise the return value is binhex encoded
     * @return string
     */
    public static function sign(string $data, string $key, bool $raw = false) : string
    {
        return Hash::hmac256($data, $key, $raw);
    }


    /**
     * Encrypts data
     * 
     * Encrypts given data with given method and key, returns a raw or base64 encoded string
     * @link https://www.php.net/manual/en/function.openssl-encrypt.php
     * 
     * @param mixed  $data  The plaintext message data to be encrypted
     * @param string $key   The key
     * @return string
     */
    public static function encrypt($data, string $key)
    {
        $data = serialize($data);

        $method  = self::AES128CTR;
        $options = OPENSSL_RAW_DATA;
        $ivlen   = openssl_cipher_iv_length($method);
        $iv      = openssl_random_pseudo_bytes($ivlen);
        
        // GCM
        // $ciphertext = openssl_encrypt($data, $method, $key, $options, $iv, $tag);
        $cipherText = openssl_encrypt($data, $method, $key, $options, $iv);
        $sign       = self::sign($cipherText, $key, true);

        return base64_encode($iv . $sign . $cipherText);
    }


    /**
     * Decrypts data
     * 
     * Takes a raw or base64 encoded string and decrypts it using a given method and key
     * @link https://www.php.net/manual/en/function.openssl-decrypt.php
     * 
     * @param string $data  The encrypted message to be decrypted
     * @param string $key   The key
     * @return mixed
     */
    public static function decrypt($data, string $key)
    {
        $method     = self::AES128CTR;
        $options    = OPENSSL_RAW_DATA;
        $ivlen      = openssl_cipher_iv_length($method);

        $data       = base64_decode($data, true);
        $iv         = substr($data, 0, $ivlen);
        $sign1      = substr($data, $ivlen, 32);
        $cipherText = substr($data, $ivlen + 32);
        $sign2      = self::sign($cipherText, $key, true);
        
        // Check sigrature hash
        if (!Hash::equals($sign1, $sign2)) {
            return null;
        }
        
        // GCM
        // $text = openssl_decrypt($ciphertext, $method, $key, $options, $iv, $tag);
        $data = openssl_decrypt($cipherText, $method, $key, $options, $iv);
        return unserialize($data);
    }
}
