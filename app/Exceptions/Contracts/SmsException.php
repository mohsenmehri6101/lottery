<?php

namespace Modules\Exception\Services\Contracts;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SmsException extends BaseException
{
    public function __construct(string $message = "Error sending SMS", int $code = Response::HTTP_INTERNAL_SERVER_ERROR, ?Throwable $previous = null, ?array $extra_data = [], ?array $errors = [])
    {
        parent::__construct($message, $code, $previous, $extra_data, $errors);
    }
}