<?php

namespace Micro\Response;

use Micro\Response;

/**
 * Class NotFoundResponse
 * @package Micro
 */
class NotFoundResponse extends Response
{
    /**
     * @var int
     */
    const CODE_NOT_FOUND = 404;

    /**
     * Set headers for 404 response
     * Set response code to "not found" http code
     */
    public function setHeaders() {
        http_response_code(self::CODE_NOT_FOUND);
    }

    /**
     * Render response
     * Not found response has no special render
     */
    public function render() {}
}