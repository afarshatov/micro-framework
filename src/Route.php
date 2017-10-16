<?php

namespace Lib;

use Exception;

abstract class Route
{
    /**
     * @var string
     */
    const CONTROLLER_ACTION_SEPARATOR = '::';

    /**
     * @var string
     */
    const PARAM_REQUEST_URI = 'PARAM_REQUEST_URI';

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var static
     */
    protected $action;

    /**
     * Route constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        if (!isset($params['action']) || empty($params['action'])) {
            throw new Exception('Action is required parameter for Route');
        }

        list($this->controller, $this->action) = explode(self::CONTROLLER_ACTION_SEPARATOR, $params['action']);
        $this->params = isset($params['params']) ? $params['params'] : [];
    }

    /**
     * @param $uri
     */
    abstract public function isMatchUri($uri);

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParamsWithValues()
    {
        if (empty($this->params)) {
            return [];
        }

        $paramsWithValues = [];

        foreach ($this->params as $key => $param) {
            switch ((string)$key) {
                case self::PARAM_REQUEST_URI:
                    $paramsWithValues[$param] = substr($_SERVER['REQUEST_URI'], 1);
                    break;
                default:
                    $paramsWithValues[$param] = isset($_REQUEST[$param]) ? $_REQUEST[$param] : null;
                    break;
            }
        }

        return $paramsWithValues;
    }
}