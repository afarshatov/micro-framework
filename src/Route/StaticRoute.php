<?php

namespace Micro\Route;

use Exception;
use Micro\Request;
use Micro\Route;

class StaticRoute extends Route
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * Simple constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        $this->uri = isset($params['uri']) ? $params['uri'] : null;

        parent::__construct($params);
    }

    /**
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return bool
     */
    public function isMatchUri($uri)
    {
        $requestUriParts = Request::getUriParts();
        $routeUriParts = Request::getUriParts($this->uri);

        if (count($routeUriParts) != count($requestUriParts)) {
            return false;
        }

        foreach ($routeUriParts as $index => $routeUriItem) {
            if (!isset($requestUriParts[$index]) || $routeUriItem != $requestUriParts[$index]) {
                return false;
            }
        }

        return true;
    }
}