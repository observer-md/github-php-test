<?php
namespace vendor\libraries;

/**
 * Jwt token class
 * 
 * @link https://jwt.io/introduction/
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Jwt
{
    public static function make(array $payload, $key)
    {
        // add header
        $token = Hash::base64Encode(json_encode([
            'alg' => 'HS256', //SHA256
            'typ' => 'JWT',
        ]), true)
        . '.'
        // add payload
        . Hash::base64Encode(json_encode($payload), true);
        
        // add signature
        return $token . '.' . Hash::base64Encode(Hash::hmac($token, $key, Hash::HASH_ALGO_SHA256, true), true);
    }
}