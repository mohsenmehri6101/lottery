<?php

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Exception\Entities\ExceptionModel;
use Modules\Exception\Services\Contracts\BaseException;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('exception_response_exception')) {
    function exception_response_exception(
        Request   $request,
        Throwable $exception = null,
                  $status_code = Response::HTTP_INTERNAL_SERVER_ERROR,
                  $message = '',
                  $errors = [],
                  $extra_data = []
    ): \Illuminate\Http\Response|JsonResponse
    {
        if (
            $exception != null
        ) {
            $extract_data_exception = BaseException::extract_data_exception($exception);
            /**
             * @var int $status_code
             * @var string $message
             * @var array $errors
             * @var array $extra_data
             */
            extract($extract_data_exception);
        }
        if ($request->wantsJson()) {
            $app_env = env('APP_ENV', 'production');
            if(!$app_env === 'production'){
                 $message = $exception->getMessage();
            }

            return ResponseHelper::responseDefault(data: $extra_data, message: $message, statusCode: $status_code, errors: $errors);
        }
        return BaseException::responseWebPage($status_code, $message, $errors);
    }
}

if (!function_exists('get_message_exception_super_admin')) {
    function get_message_exception_super_admin($exception)
    {
        $exception_class_name = $exception instanceof Exception ? class_basename($exception) : $exception;
        $message = '';
        if ($exception_model = ExceptionModel::query()->where('exception', $exception_class_name)->first()) {
            $message = $exception_model?->message ?? '';
        }
        return $message;
    }
}
