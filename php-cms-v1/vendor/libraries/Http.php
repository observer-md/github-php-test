<?php
namespace vendor\libraries;

/**
 * Http library class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Http
{
    /**
     * Build route relative url
     * 
     * @param string    $controller     Controller name
     * @param string    $action         Action name
     * @param array     $params         Additional query parameters
     * 
     * @return string
     */
    public static function route($controller, $action = 'index', $params = [])
    {
        $server = \vendor\core\Register::init()->getRequest('server');
        
        // $url = $server->get('SCRIPT_NAME') . '?c=' . $controller . '&a=' . $action;
        $url   = $server->get('REQUEST_URI');
        $query = $server->get('QUERY_STRING');
        if ($query) {
            $url = str_replace('?' . $query, '', $url);
        }
        
        $url .= '?c=' . $controller . '&a=' . $action;

        // add query params
        if ($params) {
            $url .= '&' . http_build_query($params);
        }

        return $url;
    }


    /**
     * Make API request
     * 
     * @param string $url       Api URL
     * @param array  $post      Post params
     * @param array  $headers   Http headers
     * 
     * @return array
     */
    public static function request($url, array $post = null, array $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
       
        if(!empty($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }
          
        // $headers = [
        //   'Accept: application/vnd.github.v3+json, application/json',
        //   'User-Agent: https://example-app.com/'
        // ];
       
        // if(isset($_SESSION['access_token']))
        //   $headers[] = 'Authorization: Bearer '.$_SESSION['access_token'];
       
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       
        $response = curl_exec($ch);
        return json_decode($response, true);
    }
}