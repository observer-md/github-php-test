<?php
namespace Core\Support\Cryptography;

/**
 * Hash class
 * 
 * @package Core\Support\Secure
 * @author  GEE
 */
class Hash
{
    /**
     * Hashing algorithms
     */
    const MD5      = 'md5';
    const SHA1     = 'sha1';
    const SHA256   = 'sha256';
    const SHA512   = 'sha512';


    /**
     * Generate a hash value
     * 
     * @param string $algo      Name of selected hashing algorithm
     * @param string $data      Message to be hashed
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function hash(string $algo, string $data, bool $raw = false) : string
    {
        return hash($algo, $data, $raw);
    }


    /**
     * Generate MD5 hash value
     * 
     * @param string $data      Message to be hashed
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function md5(string $data, bool $raw = false) : string
    {
        return self::hash(self::MD5, $data, $raw);
    }


    /**
     * Generate SHA1 hash value
     * 
     * @param string $data      Message to be hashed
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function sha1(string $data, bool $raw = false) : string
    {
        return self::hash(self::SHA1, $data, $raw);
    }


    /**
     * Generate SHA256 hash value
     * 
     * @param string $data      Message to be hashed
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function sha256(string $data, bool $raw = false) : string
    {
        return self::hash(self::SHA256, $data, $raw);
    }


    /**
     * Generate SHA512 hash value
     * 
     * @param string $data      Message to be hashed
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function sha512(string $data, bool $raw = false) : string
    {
        return self::hash(self::SHA512, $data, $raw);
    }


    /**
     * Timing attack safe string comparison
     * 
     * @param string $known     The string of known length to compare against
     * @param string $user      The user-supplied string
     * @return bool
     */
    public static function equals(string $known , string $user) : bool
    {
        return hash_equals($known, $user);
    }


    /**
     * Generate a keyed hash value using the HMAC method
     * 
     * @param string $algo      Name of selected hashing algorithm
     * @param string $data      Message to be hashed
     * @param string $key       Shared secret key used for generating the HMAC variant of the message digest
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function hmac(string $algo, string $data, string $key, bool $raw = false) : string
    {
        return hash_hmac($algo, $data, $key, $raw);
    }


    /**
     * Generate a keyed hash value using the HMAC method SHA256
     * 
     * @param string $data      Message to be hashed
     * @param string $key       Shared secret key used for generating the HMAC variant of the message digest
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function hmac256(string $data, string $key, bool $raw = false) : string
    {
        return self::hmac(self::SHA256, $data, $key, $raw);
    }


    /**
     * Generate a keyed hash value using the HMAC method SHA512
     * 
     * @param string $data      Message to be hashed
     * @param string $key       Shared secret key used for generating the HMAC variant of the message digest
     * @param bool   $raw       When set to TRUE, outputs raw binary data. FALSE outputs lowercase hexits
     * @return string
     */
    public static function hmac512(string $data, string $key, bool $raw = false) : string
    {
        return self::hmac(self::SHA512, $data, $key, $raw);
    }


    /**
     * Creates a password hash
     * 
     * @link https://www.php.net/manual/en/function.password-hash.php
     * 
     * @param string $password      The user's password
     * @return string
     */
    public static function password(string $password) : string
    {
        $algo    = PASSWORD_DEFAULT;
        $options = ['cost' => 12];
        return password_hash($password, $algo, $options);
    }


    /**
     * Verifies that a password matches a hash
     * 
     * @link https://www.php.net/manual/en/function.password-verify.php
     * 
     * @param string $password      The user's password
     * @param string $hash          A hash created by password_hash()
     * @return bool
     */
    public static function passwordVerify(string $password, string $hash) : bool
    {
        return password_verify($password, $hash);
    }
}
