<?php

namespace Micro;

use Exception;

abstract class Validator
{
    /**
     * @var array
     */
    protected $messages;

    /**
     * @var string|int
     */
    protected $error;

    /**
     * @param mixed $value
     * @return bool
     */
    abstract public function isValid($value);

    /**
     * @param array $messages
     */
    public function __construct(array $messages = []) {
        $this->messages = $messages;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getErrorMessage() {
        if (empty($this->error)) {
            return null;
        }

        if (isset($this->messages[$this->error])) {
            return $this->messages[$this->error];
        }

        throw new Exception("Not found error for code {$this->error}");
    }

    /**
     * @param string|int $error
     * @return void
     */
    protected function setErrorCode($error)
    {
        $this->error = $error;
    }
}