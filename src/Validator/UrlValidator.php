<?php

namespace Lib\Validator;

use Lib\Validator;

class UrlValidator extends Validator
{
    /**
     * @var string
     */
    const MESSAGE_INVALID = 'INVALID';

    /**
     * @var string
     */
    const URL_REGEXP = '#(([PROTOCOLS])://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';

    /**
     * @var string
     */
    const PROTOCOL_HTTP = 'http';

    /**
     * @var string
     */
    const PROTOCOL_HTTPS = 'https';

    /**
     * @var array
     */
    protected static $allowedProtocolsDefault = [
        self::PROTOCOL_HTTP,
        self::PROTOCOL_HTTPS
    ];

    /**
     * @var array
     */
    protected $allowedProtocols;

    /**
     * UrlValidator constructor.
     * @param array $messages
     * @param array $allowedProtocols
     */
    public function __construct(array $messages, array $allowedProtocols = [])
    {
        parent::__construct($messages);
        $this->allowedProtocols = empty($allowedProtocols) ? self::$allowedProtocolsDefault : $allowedProtocols;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (preg_match($this->getUrlRegexp(), $value) === 0) {
            $this->setErrorCode(self::MESSAGE_INVALID);

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getUrlRegexp()
    {
        return str_replace('[PROTOCOLS]', implode('|', $this->allowedProtocols), self::URL_REGEXP);
    }
}