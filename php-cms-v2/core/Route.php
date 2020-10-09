<?php
namespace Core;


/**
 * Route class
 * 
 * @package     Core
 * @author      GEE
 */
class Route
{
    /**
     * Routes list
     * @var array
     */
    public static $routes = [];


    /**
     * Add route with HTTP method
     * 
     * @param string $method        HTTP method
     * @param string $route         URI route
     * @param string $controller    Controller
     */
    public static function match(string $method, string $route, string $controller)
    {
        self::$routes["{$method}:{$route}"] = [
            'route'      => trim($route),
            'controller' => trim($controller),
            'method'     => trim($method),
        ];
    }


    /**
     * Add GET route
     * 
     * @param string $route         URI route
     * @param string $controller    Controller
     */
    public static function get(string $route, $controller)
    {
        self::match('GET', $route, $controller);
    }


    /**
     * Add POST route
     * 
     * @param string $route         URI route
     * @param string $controller    Controller
     */
    public static function post(string $route, $controller)
    {
        self::match('POST', $route, $controller);
    }


    /**
     * Add PUT route
     * 
     * @param string $route         URI route
     * @param string $controller    Controller
     */
    public static function put(string $route, $controller)
    {
        self::match('PUT', $route, $controller);
    }


    /**
     * Add DELETE route
     * 
     * @param string $route         URI route
     * @param string $controller    Controller
     */
    public static function delete(string $route, $controller)
    {
        self::match('DELETE', $route, $controller);
    }


    /**
     * 
     */
    public static function validate()
    {
        $route  = $_SERVER['REDIRECT_URL'] ?? '';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $values     = [];
        $controller = null;
        foreach (self::$routes as $rule) {
            // check path
            $pattern = '/^' . str_replace('/', '\/', $rule['route']) . '$/';
            if (preg_match($pattern, $route, $matches)) {
                // check method
                if ($rule['method'] === $method) {
                    $controller = $rule['controller'];
                    $values = array_slice($matches, 1);
                    break;
                }
            }
        }
        
        if (!$controller) {
            throw new \Exception('Route does not exists.');
        }

        $pos = strpos($controller, '@');

        return [
            'controller' => substr($controller, 0, $pos),
            'action'     => substr($controller, $pos+1),
            'values'     => $values,
        ];

        /*
        $controller = $config['controller'];
        $pos    = strpos($controller, '@');
        $class  = substr($controller, 0, $pos);
        $action = substr($controller, $pos+1);
        
        // check class method
        $ref = new ReflectionClass($class);
        if (!$ref->hasMethod($action)) {
            throw new Exception("Method [{$action}] does not exixts.");
        }

        // get method
        $method = $ref->getMethod($action);
        if (!$method->isPublic()) {
            throw new Exception("Action not available [{$action}].");
        }

        // get method parameters
        $args = [];
        foreach ($method->getParameters() as $i => $param) {
            $name    = $param->getName();
            $default = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
            
            $args[$name] = $values[$i] ?? $default;
        }

        // execute class method and pass method params
        call_user_func_array([$ref->newInstance(), $method->getName()], $args);
        */
    }


    /**
     * 
     */
    public function show()
    {
        var_dump(self::$routes);
    }
}
