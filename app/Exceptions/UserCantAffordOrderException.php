<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class UserCantAffordOrderException extends Exception
{
    public function __construct(string $message = 'User can\'t afford this order.', int $code = 415, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
