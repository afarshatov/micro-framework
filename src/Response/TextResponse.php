<?php

namespace Lib\Response;

use Lib\Response;

/**
 * Class TextResponse
 * @package Lib\Response
 */
class TextResponse extends Response
{

    /**
     * TextResponse constructor.
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * No special headers
     */
    public function setHeaders() {}

    /**
     * Only renders text
     */
    public function render()
    {
        echo $this->content;
    }
}