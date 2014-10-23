<?php

/**
 * @package     Synapse
 * @subpackage  ModuleRouter
 */

defined('_INIT') or die;

class ModuleRouter {

    protected $router;
    protected $module;
    protected $baseURL;

    public function __construct($module, $url, $router)
    {
        $this->router   = $router;
        $this->module   = $module;
        $this->baseURL  = $url;
    }

    public function getRoutes()
    {

    }

    /**
     * Add a GET route
     * @param String $segments
     * @param String $action
     */
    public function get($segments, $action)
    {
        return $this->map(array('GET'), $segments, $action);
    }

    /**
     * Add a POST route
     * @param String $segments
     * @param String $action
     */
    public function post($segments, $action)
    {
        return $this->map(array('POST'), $segments, $action);
    }

    /**
     * Add a PUT route
     * @param String $segments
     * @param String $action
     */
    public function put($segments, $action)
    {
        return $this->map(array('PUT'), $segments, $action);
    }

    /**
     * Add a DELETE route
     * @param String $segments
     * @param String $action
     */
    public function delete($segments, $action)
    {
        return $this->map(array('DELETE'), $segments, $action);
    }

    /**
     * Map a route
     * @param Array $type
     * @param String $segments
     * @param String $action
     */
    public function map($type, $segments, $action)
    {
        $route = new Route();

        $route->setUrl($this->baseURL.$segments)
              ->setMethods($type)
              ->setAction($action)
              ->setControllerPath(MODULES.'/'.$this->module.'/controllers');

        $route->moduleName = $this->module;
        $route->baseURL = $this->baseURL;

        $this->router->addRoute($route);
        $this->router->reorderRoutes();

        return $this;
    }

}