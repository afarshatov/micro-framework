<?php

namespace Micro\Controller;

use Exception;
use Micro\Controller;
use Micro\Response;
use Micro\Response\JsonResponse;
use Micro\Response\TextResponse;

/**
 * Class ErrorController
 * @package Micro
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