<?php

namespace Lib\Controller;

use Lib\Controller;
use Lib\Response\TemplateResponse;

class TemplateController extends Controller
{
    protected function render($template, $params)
    {
        return new TemplateResponse($template, $params);
    }
}