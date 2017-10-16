<?php

namespace Lib\Response;

use Lib\Response;
use Exception;

/**
 * Class RedirectResponse
 * @package Lib
 */
class RedirectResponse extends Response
{
    /**
     * @var int
     */
    const CODE_MOVED_PERMANENTLY = 301;

    /**
     * @var int
     */
    const CODE_MOVED_TEMPORARILY = 302;

    /**
     * @var int
     */
    const CODE_REDIRECT_PERMANENT = 308;

    /**
     * @var int
     */
    const CODE_REDIRECT_TEMPORARILY = 307;

    /**
     * @var string
     */
    protected $redirectTo;

    /**
     * @var int
     */
    protected $redirectCode;

    /**
     * RedirectResponse constructor.
     * @param string $redirectTo
     * @param int $redirectCode
     * @throws Exception
     */
    public function __construct($redirectTo, $redirectCode = self::CODE_MOVED_PERMANENTLY)
    {
        if (empty($redirectTo)) {
            throw new Exception('Can not redirect to empty url');
        }

        $this->redirectTo = $redirectTo;
        $this->redirectCode = $redirectCode;
    }

    /**
     * Set response headers
     * Set http redirect code
     */
    public function setHeaders()
    {
        http_response_code($this->redirectCode);
    }

    /**
     * Render response
     * Set header location header
     */
    public function render()
    {
        header("Location: {$this->redirectTo}");
    }
}