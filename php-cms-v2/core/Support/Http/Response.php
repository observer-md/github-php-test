<?php
namespace Core\Support\Http;


/**
 * HTTP Response class
 * 
 * @package     Core
 * @author      GEE
 */
class Response
{
    /**
     * Attaching Headers To Responses
     */
    public function header($name, $value)
    {
        return $this;
    }


    /**
     * Redirect
     */
    public function redirect($url = null)
    {

    }

    /**
     * Set Responses status
     */
    public function status($status = 200)
    {

    }

    /**
     * JSON Responses
     */
    public function json($data = null)
    {

    }


    /**
     * File Downloads
     * The method may be used to generate a response that forces the user's browser to download the file at the given path. 
     */
    public function download($pathToFile, $name = null, $headers = null)
    {

    }


    /**
     * File Responses
     * The method may be used to display a file, such as an image or PDF, directly in the user's browser instead of initiating a download. 
     */
    public function file($pathToFile, $headers = null)
    {

    }

    

    /**
     * Set cookie
     */
    public function cookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly)
    {

    }
}
