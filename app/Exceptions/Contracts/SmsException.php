<?php

namespace App\Exceptions\Contracts;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Modules\Exception\Services\Contracts\BaseException;

class SmsException extends BaseException
{
    public function __construct(string $message = "Error sending SMS", int $code = Response::HTTP_INTERNAL_SERVER_ERROR, ?Throwable $previous = null, ?array $extra_data = [], ?array $errors = [])
    {
        parent::__construct($message, $code, $previous, $extra_data, $errors);
    }
}
