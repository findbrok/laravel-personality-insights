<?php

namespace FindBrok\PersonalityInsights\Exceptions;

use Exception;
use RuntimeException;

class MissingParameterContentItemException extends RuntimeException
{
    /**
     * Default error message.
     *
     * @var string
     */
    protected $message = 'Missing Some parameters for ContentItem';

    /**
     * Create a new instance of MissingParameterContentItemException.
     *
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        // Format message
        $message = 'ContentItem Error: ' . (($message != '') ? $message : $this->message);
        // Call parent exception
        parent::__construct($message, $code, $previous);
    }
}
