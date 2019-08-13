<?php
namespace vendor\core;

/**
 * Application Controller class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Application
{
    /**
     * 
     */
    public function __construct()
    {}

    public function run(array $config)
    {
        $this->init($config);
        $this->resolve();
    }

    /**
     * Init all parameters
     */
    private function init(array $config)
    {
        // register config
        Register::init()->setConfig($config);

        // set timezone
        if (isset($config['timezone'])) {
            date_default_timezone_set($config['timezone']);
        }

        // register requests
        Register::init()->setRequest('get', new Request('GET'));
        Register::init()->setRequest('post', new Request('POST'));
        Register::init()->setRequest('server', new Request('SERVER'));
        Register::init()->setRequest('cookie', new Request('COOKIE'));
        Register::init()->setRequest('session', new Request('SESSION'));

        // register DB
        Register::init()->set('database', DB::init(Register::init()->getConfig('database')));
    }


    /**
     * Load controller & call action
     */
    private function resolve()
    {
        /*
         * handle controller
         */
        $defaultController = Register::init()->getConfig('defaultController');
        $controllerName = Register::init()->getRequest('get')->get('c', $defaultController);

        $controllerNamespace = '\\app\\controllers\\';

        // check sub path
        $pos = strrpos($controllerName, '/');
        if ($pos > 0) {
            $subPath = substr($controllerName, 0, $pos);
            $controllerName = substr($controllerName, $pos + 1);
            $controllerNamespace = $controllerNamespace . str_replace('/', '\\', $subPath);
        }
        
        $controllerName = ucwords($controllerName) . 'Controller';
        $controllerName = $controllerNamespace . '\\' . $controllerName;
        $controllerName = str_replace('\\\\', '\\', $controllerName);

        /*
         * handle action
         */
        $defaultAction = Register::init()->getConfig('defaultAction');
        $actionName = Register::init()->getRequest('get')->get('a', $defaultAction);
        $actionName = 'action' . ucwords($actionName);

        // init controller
        $controller = new $controllerName([
            'controllerName' => $controllerName,
            'actionName'     => $actionName,
        ]);
        
        // call action
        $controller->{$actionName}();
    }
}