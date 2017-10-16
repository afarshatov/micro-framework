<?php

namespace Micro;

class Request
{
    /**
     * @var string
     */
    const URI_SEPARATOR = '/';

    /**
     * @var string
     */
    const URI_PARAMS_SEPARATOR = '?';

    /**
     * Get request uri without leading slash
     * @return string
     */
    public static function getUri()
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        return implode(self::URI_SEPARATOR, self::getUriParts());
    }

    /**
     * @param string $uri
     * @return array
     */
    public static function getUriParts($uri = null)
    {
        $uri = is_null($uri)
            ? isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''
            : $uri;

        if (empty($uri)) {
            return [];
        }

        if (($paramsPosition = strpos($uri, self::URI_PARAMS_SEPARATOR)) !== false) {
            $uri = substr($uri, 0, $paramsPosition);
        }

        $uriParts = explode(self::URI_SEPARATOR, $uri);

        return array_values(array_filter($uriParts, function($item) {
            return !empty($item);
        }));
    }
}