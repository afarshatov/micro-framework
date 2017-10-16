<?php

namespace Lib;

use Exception;

/**
 * Class Controller
 * @package Lib
 */
class Controller
{
    /**
     * @var Route
     */
    protected $route;

    /**
     * @param Route $route
     * @return Controller
     * @throws Exception
     */
    public static function factory(Route $route)
    {
        $controllerName = $route->getController();

        try {
            $controller = new $controllerName($route);
        } catch (Exception $e) {
            throw new Exception('Can not create controller by route', -1, $e);
        }

        return $controller;
    }

    /**
     * Controller constructor.
     * @param Route $route
     */
    protected function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @return Response
     */
    public function execute()
    {
        $response = call_user_func_array([$this, $this->route->getAction()], $this->route->getParamsWithValues());

        return $this->generateResponse($response);
    }

    /**
     * @param mixed $response
     * @return Response
     */
    protected function generateResponse($response)
    {
        return $response;
    }

    /**
     * @return bool
     */
    protected static function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}