<?php

namespace Micro\Controller;

use Micro\Controller;
use Micro\Response\JsonResponse;

class AjaxController extends Controller
{
    /**
     * @param bool $result
     * @param array $content
     * @return JsonResponse
     */
    protected function sendResponse($result, array $content = [])
    {
        return new JsonResponse($result, $content);
    }
}