<?php
namespace IPG\Base\Support\Http;

use IPG\Base\Support\Random;
use IPG\Base\Support\Base64;

/**
 * HTTP CSRF token validation class
 * 
 * @package IPG\Base\Support\Http
 * @author  GEE
 */
class Csrf
{
    /**
     * Validation is ON
     * @var bool
     */
    private static $on      = true;
    /**
     * Session CSRF token name
     * @var string
     */
    private static $name    = 'csrf-token';
    /**
     * Request instance
     * @var Request
     */
    private static $request = null;
    /**
     * Token generate type
     * 0 - use same token
     * 1 - refresh token every validation
     * 
     * @var int
     */
    private static $type    = 0;


    /**
     * Return Request
     * 
     * @return Request
     */
    private static function request()
    {
        if (!self::$request) {
            self::$request = new \IPG\Base\Request();
        }
        return self::$request;
    }


    /**
     * Turn validation ON/OFF
     * @param bool $on
     */
    public static function on($on = true)
    {
        self::$on = $on;
    }


    /**
     * Set session CSRF token
     * 
     * @param bool $refresh
     * @return string
     */
    public static function set($refresh = false)
    {
        if ($refresh || !self::request()->session(self::$name)) {
            $_SESSION[self::$name] = Base64::encode(Random::bytes(64));
        }
        return self::request()->session(self::$name);
    }


    /**
     * Return session CSRF token
     * 
     * @return string
     */
    public static function get()
    {
        return self::set();
    }


    /**
     * Validate CSRF token
     * 
     * @param string $token
     * @return bool
     */
    public static function validate($token)
    {
        // Invalid request due to CSRF token error.
        if ($csrf = self::get()) {

            // refresh token
            if(self::$type > 0) {
                self::set(true);
            }
            
            return hash_equals($csrf, strval($token));
        }
        return true;
    }


    /**
     * Validate CSRF request
     * 
     * Pass CSRF token in POST or HEADER
     * For API calls CSRF token can be passed in request header variable X-XSRF-TOKEN
     * 
     * @return bool
     */
    public static function validateRequest()
    {
        if (!self::$on) {
            return true;
        }

        // get CSRF token from POST or header X-CSRF-TOKEN
        $csrf = self::request()->post(self::$name, '')
            ?: self::request()->header('X-CSRF-TOKEN', '')
            ?: self::request()->server('HTTP_X_CSRF_TOKEN', '');
        
        return self::validate($csrf);
    }


    /**
     * Validate CSRF post request
     * 
     * @method POST
     * @return bool
     */
    public static function validatePost()
    {
        if (!self::request()->isPost()) {
            return true;
        }
        return self::validateRequest();
    }


    /**
     * Return CSRF html hidden-field/meta-tag
     * 
     * @param bool $meta    Return CSRF meta tag
     * @return string
     */
    public static function html($meta = false)
    {
        if ($meta) {
            return '<meta name="' . self::$name . '" content="' . self::get() . '">';
        }
        return '<input type="hidden" name="' . self::$name . '" value="' . self::get() . '" />';
    }
}
