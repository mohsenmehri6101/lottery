<?php

namespace Modules\Exception\Services\Contracts;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use function filled;
use function trans;

trait BaseExceptionTrait
{
    public static function getMessagePriority($exception=null,$default_message=null): ?string
    {
        $message = method_exists($exception, 'getMessage') ? $exception->getMessage() : '';
        return filled($message) && is_string_persian($message) ? $message : $default_message ?? trans('custom.defaults.exceptions.500');
    }

    public static function getCodePriority($exception=null,$default_code=null)
    {
        # $exception = $exception ?? $this;
        $status_code = method_exists($exception, 'getCode') ? $exception->getCode() : 0;
        $status_code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $status_code;
        $status_code = $status_code ?? HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR/* 500 */;
        return filled($status_code) && $status_code ? $status_code : $default_code ?? HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR/* 500 */ ;
    }

    public static function getErrorsPriority($exception=null): array
    {
        # $exception = $exception ?? $this;
        return method_exists($exception, 'errors') ? $exception->errors() : [];
    }

    public static function getExtraData($exception): ?array
    {
        return property_exists($exception, 'extra_data') ? $exception->extra_data : [];
    }
}
