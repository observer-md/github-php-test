<?php
namespace Core\Support\Http;

use Core\Support\Filter;

/**
 * HTTP Request class
 * Class uses to retrive HTTP request data
 * 
 * @package Core\Support\Http
 * @author  GEE
 */
class Request
{
    /**
     * Return GET param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function get($name = null, $default = null, $filter = null)
    {
        return Filter::param($_GET, $name, $default, $filter);
    }


    /**
     * Return POST param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function post($name = null, $default = null, $filter = null)
    {
        return Filter::param($_POST, $name, $default, $filter);
    }


    /**
     * Return REQUEST param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function request($name = null, $default = null, $filter = null)
    {
        return Filter::param($_REQUEST, $name, $default, $filter);
    }


    /**
     * Return FILES param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function file($name = null, $default = null, $filter = null)
    {
        return Filter::param($_FILES, $name, $default, $filter);
    }


    /**
     * Return ENV param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function env($name = null, $default = null, $filter = null)
    {
        return Filter::param($_ENV, $name, $default, $filter);
    }


    /**
     * Return COOKIE param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function cookie($name = null, $default = null, $filter = null)
    {
        return Filter::param($_COOKIE, $name, $default, $filter);
    }


    /**
     * Return SESSION param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function session($name = null, $default = null, $filter = null)
    {
        return Filter::param($_SESSION, $name, $default, $filter);
    }


    /**
     * Return SERVER param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @return mixed
     */
    public function server($name = null, $default = null, $filter = null)
    {
        return Filter::param($_SERVER, $name, $default, $filter);
    }


    /**
     * Return headers param
     * 
     * @param string $name      Param name
     * @param mixed  $default   Default value
     * @param mixed  $filter    Value filter type
     * 
     * @link https://www.php.net/manual/en/function.getallheaders.php
     * 
     * @return mixed
     */
    public function header($name = null, $default = null, $filter = null)
    {
        $data = [];
        if (function_exists('getallheaders')) {
            $data = getallheaders();
        }
        return Filter::param($data, $name, $default, $filter);
    }


    /**
     * Return IP address
     * 
     * @link http://itman.in/en/how-to-get-client-ip-address-in-php/
     * 
     * @return null|string
     */
    public function ip()
    {
        // check for shared internet/ISP IP
        if ($ip = $this->server('HTTP_CLIENT_IP', null, 'ip')) {
            return $ip;
        }
        
        // check for IPs passing through proxies
        if ($ips = $this->server('HTTP_X_FORWARDED_FOR')) {
            // check if multiple IPs exist in var
            if (strpos($ips, ',') !== false) {
                $iplist = explode(',', $ips);
                foreach ($iplist as $ip) {
                    if ($ip = Filter::value($ip, null, 'ip')) {
                        return $ip;
                    }
                }
            } else {
                if ($ip = Filter::value($ips, null, 'ip')) {
                    return $ip;
                }
            }
        }
        
        // check others
        return $this->server('HTTP_X_FORWARDED', null, 'ip')
            ?: $this->server('HTTP_X_CLUSTER_CLIENT_IP', null, 'ip')
            ?: $this->server('HTTP_FORWARDED_FOR', null, 'ip')
            ?: $this->server('HTTP_FORWARDED', null, 'ip')
            ?: $this->server('REMOTE_ADDR', null, 'ip');
    }


    /**
     * Return HTTP User Agent
     * 
     * @return null|string
     */
    public function agent()
    {
        return $this->server('HTTP_USER_AGENT', null, 'str');
    }


    /**
     * Return http/https protocol
     * @return string
     */
    public function protocol()
    {
        return $this->isSSL() ? 'https' : 'http';
    }

    
    /**
     * Check if HTTPS/SSL
     * 
     * @return boolean
     */
    public function isSSL()
    {
        return $this->server('HTTPS') == 'on'
            || $this->server('HTTPS', null, 'int') == 1
            || $this->server('SERVER_PORT', null, 'int') == 443
            || $this->server('HTTP_X_FORWARDED_SSL') == 'on'
            || $this->server('HTTP_X_FORWARDED_PROTO') == 'https';
    }


    /**
     * Check if AJAX request
     * 
     * @return boolean
     */
    public function isAjax()
    {
        $value = $this->header('X-Requested-With', null, 'str')
            ?: $this->server('HTTP_X_REQUESTED_WITH', null, 'str');

        return strtolower($value) === 'xmlhttprequest';
    }


    /**
     * Check HTTP method
     * 
     * @param string $method    Method name
     * @return bool 
     */
    public function isMethod($method)
    {
        return $this->server('REQUEST_METHOD') == $method;
    }


    /**
     * Check if HTTP method GET
     * @return bool
     */
    public function isGet()
    {
        return $this->isMethod('GET');
    }


    /**
     * Check if HTTP method POST
     * @return bool
     */
    public function isPost()
    {
        return $this->isMethod('POST');
    }


    /**
     * Check if HTTP method PUT
     * @return bool
     */
    public function isPut()
    {
        return $this->isMethod('PUT');
    }


    /**
     * Check if HTTP method DELETE
     * @return bool
     */
    public function isDelete()
    {
        return $this->isMethod('DELETE');
    }
}
