<?php

namespace Lib\Response;

use Lib\Response;
use Lib\Template;

class TemplateResponse extends Response
{
    /**
     * @var Template
     */
    protected $template;

    /**
     * ViewResponse constructor.
     * @param string $templatePath
     * @param array $params
     */
    public function __construct($templatePath, array $params = [])
    {
        $this->template = new Template($templatePath, $params);
    }

    public function setHeaders()
    {
        header('Content-Type: text/html');
    }

    public function render()
    {
        $this->template->render();
    }
}