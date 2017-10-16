<?php

namespace Micro\Response;

use Micro\Response;

/**
 * Class TextResponse
 * @package Micro\Response
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