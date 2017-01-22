<?php

namespace FindBrok\PersonalityInsights\Exceptions;

use Exception;
use RuntimeException;

class InvalidCredentialsName extends RuntimeException
{
    /**
     * Default error message.
     *
     * @var string
     */
    protected $message = 'Specified Credentials Name is invalid.';

    /**
     * InvalidCredentialName constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        // Format message
        $message = 'Credentials Error: ' . (($message != '') ? $message : $this->message);
        // Call parent exception
        parent::__construct($message, $code, $previous);
    }
}
