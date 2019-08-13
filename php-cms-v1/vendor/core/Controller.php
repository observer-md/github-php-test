<?php
namespace vendor\core;

/**
 * Controller class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Controller
{
    /**
     * Controller name
     * @var string
     */
    protected $controllerName   = '';

    /**
     * Controller action name
     * @var string
     */
    protected $actionName       = '';

    /**
     * View layout title
     * @var string
     */
    protected $title            = '';

    /**
     * View layout
     * @var staring
     */
    protected $viewLayout       = 'layout';

    /**
     * View directory
     * @var staring
     */
    protected $viewDir          = '';

    /**
     * Application register
     * @var Register
     */
    protected $app;
    
    /**
     * View data
     * @var array
     */
    protected $data = [];


    /**
     * 
     */
    public function __construct($params = null)
    {
        $this->controllerName = $params['controllerName'] ?? '';
        $this->actionName     = $params['actionName'] ?? '';

        $this->app = Register::init();
    }


    /**
     * 
     */
    public function setControllerName($name)
    {
        $this->controllerName = $name;
    }


    /**
     * 
     */
    public function setActionName($name)
    {
        $this->actionName = $name;
    }


    /**
     * Build relative url
     * 
     * @param string    $controller     Controller name
     * @param string    $action         Action name
     * @param array     $params         Additional query parameters
     * 
     * @return string
     */
    public function buildPath($controller, $action = 'index', $params = [])
    {
        $server = $this->app->getRequest('server');
        
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
     * Redirect to url
     * 
     * @param string $path  Redirect path
     */
    public function redirect($path)
    {
        header('Location: ' . $path);
        exit;
    }

    /**
     * Load view
     * 
     * @param string    $path   View path
     * @param array     $data   View data
     * 
     * @return string
     */
    public function viewPartial($path, $data = [])
    {
        // load view
        $path = DIR_APP . '/views/' . $this->viewDir . $path . '.php';
        ob_start();
        include($path);
        return ob_get_clean();
    }


    /**
     * Load view and layout
     * 
     * @param string    $path   View path
     * @param array     $data   View data
     * 
     * @return string
     */
    public function view($path, $data = [])
    {
        // load view
        $context = $this->viewPartial($path, $data);
        
        // load layout
        $path = DIR_APP . '/views/' . $this->viewLayout . '.php';
        ob_start();
        include($path);
        return ob_get_clean();
    }


    /**
     * Print JSON data
     * 
     * @param array $data   Some data
     * @return void
     */
    public function json($data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        return;
    }



    protected $tagsMeta     = [
        // '<meta charset="UTF-8">',
    ];

    protected $tagsLink     = [];
    protected $tagsScript   = [];

    /**
     * Add meta tag
     * 
     * <meta charset="UTF-8">
     * <meta name="description" content="Free Web tutorials">
     * <meta name="viewport" content="width=device-width, initial-scale=1.0">
     * 
     */
    public function addTagMeta($name, $content)
    {
        $this->tagsMeta[] = '<meta name="' . $name . '" content="' . $content . '">';
    }

    /**
     * Add link tag
     * 
     * <link rel="stylesheet" type="text/css" href="theme.css">
     */
    public function addTagLink($path)
    {
        $this->tagsLink[] = '<link rel="stylesheet" type="text/css" href="' . $path . '">';
    }


    /**
     * Add script tag
     * 
     * <script type="text/javascript" src="theme.js">
     */
    public function addTagScript($path)
    {
        $this->tagsScript[] = '<script type="text/javascript" src="' . $path . '"></script>';
    }

    /**
     * Return head meta tags
     * @return script
     */
    public function getHeadMeta()
    {
        return "\n" . join("\n", $this->tagsMeta) . "\n"; 
    }

    /**
     * Return head link tags
     * @return script
     */
    public function getHeadLink()
    {
        return "\n" . join("\n", $this->tagsLink) . "\n"; 
    }

    /**
     * Return head script tags
     * @return script
     */
    public function getHeadScript()
    {
        return "\n" . join("\n", $this->tagsScript) . "\n"; 
    }
}