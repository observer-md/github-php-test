<?php
namespace Core;

/**
 * Application Controller
 * 
 * @package     Core
 * @author      GEE
 */
class Application
{
    /**
     * Run
     */
    public function run(array $config)
    {
        $this->init($config);
        $this->resolve();
    }


    /**
     * Init config
     */
    private function init(array $config)
    {
        // register config
        Register::set('config', $config);
        // set db instance
        // Register::set('database', Register::config('database'));

        Register::set('request', new \Core\Support\Http\Request());
        Register::set('response', new \Core\Support\Http\Response());
    }


    /**
     * Find and initialize controller
     */
    private function resolve()
    {
        // get route configurations
        $params = Route::validate();
        
        /**
         * Check and Init controller class and execute action
         */
        $ref = new \ReflectionClass($params['controller']);
        if (!$ref->hasMethod($params['action'])) {
            throw new \Exception("Method [{$params['action']}] does not exixts.");
        }

        // get method
        $method = $ref->getMethod($params['action']);
        if (!$method->isPublic()) {
            throw new \Exception("Action not available [{$params['action']}].");
        }

        // get method parameters
        $args = [];
        foreach ($method->getParameters() as $i => $param) {
            $name    = $param->getName();
            $default = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
            
            $args[$name] = $params['values'][$i] ?? $default;
        }

        // execute class method and pass method params
        call_user_func_array([$ref->newInstance(), $method->getName()], $args);
    }
}
