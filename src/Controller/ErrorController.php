<?php

namespace Lib\Controller;

use Exception;
use Lib\Controller;
use Lib\Response;
use Lib\Response\JsonResponse;
use Lib\Response\TextResponse;

/**
 * Class ErrorController
 * @package Lib
 */
class ErrorController extends Controller
{
    /**
     * @var Exception
     */
    protected $exception;

    /**
     * @return Response
     */
    public function renderException()
    {
        if (self::isAjax()) {
            return new JsonResponse(false, [
                'error' => $this->exception->getMessage(),
            ]);
        } else {
            return new TextResponse($this->exception->getMessage());
        }
    }

    /**
     * @param Exception $exception
     */
    public function setError(Exception $exception)
    {
        $this->exception = $exception;
    }
}