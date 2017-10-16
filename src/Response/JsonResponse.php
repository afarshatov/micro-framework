<?php

namespace Lib\Response;

use Lib\Response;
use Exception;

class JsonResponse extends Response
{
    /**
     * @var string
     */
    const KEY_RESULT = 'success';

    /**
     * @var bool
     */
    protected $result;

    /**
     * Response constructor.
     * @param boolean $result
     * @param array $content
     * @throws Exception
     */
    public function __construct($result, $content)
    {
        $this->result = $result;
        $this->content = $content;
    }

    /**
     * Set application/json header
     * @return void
     */
    public function setHeaders()
    {
        header('Content-Type: application/json');
    }

    /**
     * Renders json string
     * @return void
     */
    public function render()
    {
        echo json_encode(array_merge([
            self::KEY_RESULT => $this->result,
        ], $this->content));
    }
}