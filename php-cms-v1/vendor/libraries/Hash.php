<?php
namespace vendor\libraries;


/**
 * Hash library class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Hash
{
    const HASH_ALGO_MD5     = 'md5';
    const HASH_ALGO_SHA256  = 'sha256';
    const HASH_ALGO_SHA384  = 'sha384';
    const HASH_ALGO_SHA512  = 'sha512';

    const CIPHER_AES256CBC  = "AES-256-CBC";
    
    /**
     * Creates a password hash
     * 
     * @link https://www.php.net/manual/en/function.password-hash.php
     * 
     * @param string    $password  The user's password
     * @return string
     */
    public static function passwordHash(string $password) : string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }


    /**
     * Verifies that a password matches a hash
     * 
     * @link https://www.php.net/manual/en/function.password-verify.php
     * 
     * @param string    $password  The user's password
     * @param string    $hash      A hash created by password_hash()
     * @return bool
     */
    public static function passwordVerify(string $password, string $hash) : bool
    {
        return password_verify($password, $hash);
    }


    /**
     * Generate a hash value (message digest)
     * 
     * @link https://www.php.net/manual/en/function.hash.php
     * 
     * @param string    $data   Message to be hashed
     * @param string    $algo   Name of selected hashing algorithm (e.g. "md5", "sha256", "haval160,4", etc..)
     * @param bool      $raw    When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits.
     * @return string
     */
    public static function hash(string $data, string $algo = self::HASH_ALGO_SHA256, $raw = false) : string
    {
        return hash($algo, $data, $raw);
    }


    /**
     * Generate a keyed hash value using the HMAC method
     * 
     * @link https://www.php.net/manual/en/function.hash-hmac.php
     * 
     * @param string    $data   Message to be hashed
     * @param string    $key    Shared secret key used for generating the HMAC variant of the message digest
     * @param string    $algo   Name of selected hashing algorithm (e.g. "md5", "sha256", "haval160,4", etc..)
     * @param bool      $raw    When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function hmac(string $data, string $key, string $algo = self::HASH_ALGO_SHA256, $raw = false) : string
    {
        return hash_hmac($algo, $data, $key, $raw);
    }

    /**
     * Generates cryptographically secure pseudo-random bytes
     * 
     * @link https://www.php.net/manual/en/function.random-bytes.php
     * 
     * @param int   $length     The length of the random string that should be returned in bytes
     * @param bool  $raw        When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function randomBytes(int $length, $raw = false) : string
    {
        $bytes = random_bytes($length);
        return $raw ? $bytes : bin2hex($bytes);
    }

    /**
     * Encodes data with MIME base64
     * 
     * @link https://www.php.net/manual/en/function.base64-encode.php
     * 
     * @param string    $data   The data to encode
     * @param bool      $url    if TRUE - base64Url
     * @return string
     */
    public static function base64Encode(string $data, bool $url = false) : string
    {
        $data = base64_encode($data);
        if ($url) {
            $data = rtrim($data, '=');
            $data = strtr($data, '+/', '-_');
        }
        return $data;
    }

    /**
     * Decodes data encoded with MIME base64
     * 
     * @link https://www.php.net/manual/en/function.base64-decode.php
     * 
     * @param string    $data   The encoded data
     * @param bool      $url    if TRUE - base64Url
     * @return string
     */
    public static function base64Decode(string $data, bool $url = false) : string
    {
        if ($url) {
            $data = strtr($data, '-_', '+/');
        }
        return base64_decode($data, false);
    }

    /*
    function base64url_encode($data, $pad = null) {
        $data = str_replace(array('+', '/'), array('-', '_'), base64_encode($data));
        if (!$pad) {
            $data = rtrim($data, '=');
        }
        return $data;
    }

    function base64url_decode($data) {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
    }
    */


    /**
     * Encrypts data
     * 
     * @link https://www.php.net/manual/en/function.openssl-encrypt.php
     * 
     * @param string    $data   The plaintext message data to be encrypted
     * @param string    $key    The key
     * @return string
     */
    public static function encrypt(string $data, string $key)
    {
        $ivlen = openssl_cipher_iv_length(self::CIPHER_AES256CBC);
        $iv = openssl_random_pseudo_bytes($ivlen);
        
        // $enc_key = openssl_digest(php_uname(), 'sha256', false);
        // var_dump($enc_key);
        
        $enc = openssl_encrypt($data, self::CIPHER_AES256CBC, $key, OPENSSL_RAW_DATA, $iv);
        return self::base64Encode($iv . $enc);
    }


    /**
     * Decrypts data
     * 
     * @link https://www.php.net/manual/en/function.openssl-decrypt.php
     * 
     * @param string    $data   The encrypted message to be decrypted
     * @param string    $key    The key
     * @return string
     */
    public static function decrypt(string $data, string $key)
    {
        $data = self::base64Decode($data);
        $ivlen = openssl_cipher_iv_length(self::CIPHER_AES256CBC);
        $iv = substr($data, 0, $ivlen);
        $data = substr($data, $ivlen);

        return openssl_decrypt($data, self::CIPHER_AES256CBC, $key, OPENSSL_RAW_DATA, $iv) ?: null;
    }
}