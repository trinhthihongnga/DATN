<?php

namespace App\Exceptions;

use Throwable;

class FailException extends \Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return $this->message;
    }
}
