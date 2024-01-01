<?php

namespace App\Exceptions\Contracts;

use Modules\Exception\Services\Contracts\BaseException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DeveloperException extends BaseException
{
    public function __construct(string $message = "", int $code = null, ?Throwable $previous = null, ?array $extra_data = [], ?array $errors = [])
    {
//        $message = filled($message) ? trim($message) : 'شما فعال نیستید';
//        $code = $code ?? Response::HTTP_FORBIDDEN;
        parent::__construct($message, $code, $previous, $extra_data, $errors);
    }

}
