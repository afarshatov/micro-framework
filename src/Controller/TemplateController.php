<?php

namespace Micro\Controller;

use Micro\Controller;
use Micro\Response\TemplateResponse;

class TemplateController extends Controller
{
    protected function render($template, $params)
    {
        return new TemplateResponse($template, $params);
    }
}