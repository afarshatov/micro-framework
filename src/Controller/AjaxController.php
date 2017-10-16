<?php

namespace Lib\Controller;

use Lib\Controller;
use Lib\Response\JsonResponse;

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