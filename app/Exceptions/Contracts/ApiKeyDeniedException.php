<?php

namespace App\Exceptions\Contracts;

use Modules\Exception\Services\Contracts\BaseException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiKeyDeniedException extends BaseException
{
    public function __construct(string $message = "", int $code = Response::HTTP_FORBIDDEN, ?Throwable $previous = null, ?array $extra_data = [], ?array $errors = [])
    {
        parent::__construct($message, $code, $previous, $extra_data, $errors);
    }
}
