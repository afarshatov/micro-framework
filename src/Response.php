<?php

namespace Micro;

use Exception;
use Micro\Response\JsonResponse;

abstract class Response
{
    /**
     * @var string
     */
    protected $content;

    /**
     * Set headers specific for the response
     * @return void
     */
    abstract public function setHeaders();

    /**
     * Logic of render specific response
     * @return void
     */
    abstract public function render();

    /**
     * @return void
     */
    public function send()
    {
        $this->setHeaders();
        $this->render();
    }
}