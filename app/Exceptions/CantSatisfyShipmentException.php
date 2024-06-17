<?php

namespace App\Exceptions;

class CantSatisfyShipmentException extends \Exception
{
    public function __construct(string $message = 'Can\'t satisfy this shipment due to lack of products.', int $code = 422, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
