<?php

namespace Lib;

use Exception;
use Lib\Route\StaticRoute;
use Lib\Controller\ErrorController;

class App
{
    /**
     * @var string
     */
    const APP_DIR = 'app';

    /**
     * @var string
     */
    const CONFIGS_DIR = 'config';

    /**
     * @var string
     */
    const LOCALE_EN = 'en';

    /**
     * @var string
     */
    const ERROR_ACTION = '\Lib\Controller\ErrorController::renderException';

    /**
     * @var App
     */
    private static $instance;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var ErrorController
     */
    protected $errorController;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * App constructor.
     */
    private function __construct() {
        $this->router = Router::getInstance();
        $this->errorController = Controller::factory(new StaticRoute([
            'action' => self::ERROR_ACTION,
        ]));
    }

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $baseDir
     * @param string $locale
     */
    public function run($baseDir, $locale = self::LOCALE_EN)
    {
        $this->baseDir = $baseDir;
        $this->locale = $locale;

        try {
            $this->configure();
            $this->dispatch();
            $this->execute();
        } catch (Exception $e) {
            $this->errorController->setError($e);
            $this->execute($this->errorController);
        }
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $alias
     * @return Route
     */
    public function getRoute($alias)
    {
        return $this->router->getRoute($alias);
    }

    /**
     * @param string $database
     * @param string $collection
     * @return Storage
     */
    public function getStorage($database, $collection)
    {
        $this->storage->setDatabase($database);
        $this->storage->setCollection($collection);

        return $this->storage;
    }

    /**
     * @param Controller $controller
     */
    public function setErrorController(Controller $controller)
    {
        $this->errorController = $controller;
    }

    /**
     * Configure application
     * @return void
     */
    protected function configure()
    {
        $this->router->registerRoutes();
        $this->storage = Storage::getInstance();
    }

    /**
     * Find route and create controller
     * @throws Exception
     */
    protected function dispatch()
    {
        $route = $this->router->findRoute(Request::getUri());
        $this->controller = Controller::factory($route);
    }

    /**
     * Run action and send response
     * @param Controller $controller
     */
    protected function execute($controller = null)
    {
        if (is_null($controller)) {
            $controller = $this->controller;
        }

        $response = $controller->execute();
        $response->send();
    }
}