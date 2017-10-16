<?php

namespace Micro;

use Exception;

/**
 * Class Router
 */
class Router
{
    /**
     * @var string
     */
    const DEFAULT_ROUTE = 'DEFAULT';

    /**
     * @var string
     */
    const ROUTES_FILE = 'routes';

    /**
     * @var App
     */
    private static $instance;

    /**
     * @var Route[]
     */
    protected $routes;

    /**
     * @var Route
     */
    protected $defaultRoute;

    /**
     * @return Router
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->routes = [];
    }

    /**
     * @return Route []
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return Route
     */
    public function getDefaultRoute()
    {
        return $this->getRoute(self::DEFAULT_ROUTE);
    }

    /**
     * @param $alias
     * @return Route
     * @throws Exception
     */
    public function getRoute($alias)
    {
        if (!isset($this->routes[$alias])) {
            throw new Exception("There is no route {$alias} in application");
        }

        return $this->routes[$alias];
    }

    /**
     * @param string $uri
     * @return Route|null
     */
    public static function findRoute($uri)
    {
        $router = self::getInstance();
        $routes = $router->getRoutes();

        foreach ($routes as $routeAlias => $route) {
            if ($routeAlias === self::DEFAULT_ROUTE) {
                continue;
            }

            if ($route->isMatchUri($uri)) {
                return $route;
            }
        }

        return $router->getDefaultRoute();
    }

    /**
     * @param string $routesFile
     * @param string $routesDir
     */
    public function registerRoutes($routesFile = self::ROUTES_FILE, $routesDir = App::CONFIGS_DIR)
    {
        $routes = ConfigFile::load($routesFile, $routesDir);

        if (empty($routes)) {
            return;
        }

        foreach ($routes as $alias => $route) {
            $this->addRoute($alias, $route);
        }
    }

    /**
     * @param string $alias
     * @param Route $route
     */
    protected function addRoute($alias, Route $route)
    {
        $this->routes[$alias] = $route;
    }
}