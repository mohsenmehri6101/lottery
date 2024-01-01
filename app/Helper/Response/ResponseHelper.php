<?php

namespace App\Helper\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    public static function responseDefault(mixed $data = null, string $message = "", int $statusCode = Response::HTTP_OK, array $errors = []): JsonResponse
    {
        $response = [];
        $response['message'] = $message;
        $response['errors'] = $errors;
        $response['data'] = $data;
        $response['status'] = $statusCode;

        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_OK;
        return response()->json($response, Response::HTTP_OK);
    }
    public static function responseSuccess(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.success');
        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_OK;
        return self::responseDefault(data: $data, message: $message, statusCode: $statusCode, errors: $errors);
    }
    public static function responseFailed(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.failed');
        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_INTERNAL_SERVER_ERROR;
        return self::responseDefault(data: $data, message: $message, statusCode: $statusCode, errors: $errors);
    }
    public static function responseError(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.failed');
        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_INTERNAL_SERVER_ERROR;
        return self::responseDefault(data: $data, message: $message, statusCode: $statusCode, errors: $errors);
    }
    public static function responseSuccessDelete(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.delete_success');
        return self::responseSuccess(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseFailedDelete(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.delete_failed');
        return self::responseError(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseSuccessUpdate(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.update_success');
        return self::responseSuccess(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseFailedUpdate(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.update_failed');
        return self::responseError(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseSuccessShow(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.show_success');
        return self::responseSuccess(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseFailedShow(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.show_failed');
        return self::responseError(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseSuccessIndex(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.index_success');
        return self::responseSuccess(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseFailedIndex(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.index_failed');
        return self::responseError(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseSuccessStore(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.store_success');
        return self::responseSuccess(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
    public static function responseFailedStore(array|object $data = [], array $errors = [], string $message = "", int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $message = filled($message) ? $message : trans('custom.defaults.store_failed');
        return self::responseError(data: $data, errors: $errors, message: $message, statusCode: $statusCode);
    }
}
