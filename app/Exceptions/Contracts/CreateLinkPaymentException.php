<?php

namespace App\Exceptions\Contracts;

use Modules\Exception\Services\Contracts\BaseException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
class CreateLinkPaymentException extends BaseException
{
    public function __construct(string $message = "", int $code = null, ?Throwable $previous = null, ?array $extra_data = [], ?array $errors = [])
    {
        $message = filled($message) ? trim($message) : 'خطا در ایجاد لینک';
        $code = $code ?? Response::HTTP_LOOP_DETECTED;
        parent::__construct($message, $code, $previous, $extra_data, $errors);
    }

}
