<?php

namespace Modules\Exception\Services\Contracts;

use App\Helper\Response\ResponseHelper;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection as SupportCollection;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Exception\Entities\ExceptionModel;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Throwable;

class BaseException extends Exception
{
    use BaseExceptionTrait;

    public array $extra_data;
    public array $errors;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, array|null $extra_data = [], array|null $errors = [])
    {
        $this->extra_data = $this->makeStandardDate($extra_data) ?? [];
        $this->errors = $this->makeStandardDate($errors) ?? [];
        $code = $code ?? HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR/*500*/;
        parent::__construct($message, $code, $previous);
    }

    public function makeStandardDate(array|null $data): ?array
    {
        if ($data instanceof SupportCollection || $data instanceof EloquentCollection) {
            $data = $data->toArray();
        }
        if (!is_array($data) && $data && filled($data)) {
            $data = [$data];
        }
        if ($data == null) {
            $data = [];
        }
        return $data;
    }

    private static function save_exception($exception_name): \Illuminate\Database\Eloquent\Model|Builder|null
    {
        return ExceptionModel::query()->firstOrCreate(['exception' => $exception_name]) ?? null;
    }

    #[ArrayShape(['status_code' => "int", 'message' => "null|string", 'errors' => "array", 'extra_data' => "array|null"])]
    public static function extract_data_exception(Throwable $exception): array
    {
        $exception_name = class_basename($exception);
        /** @var ExceptionModel $exception_model */
        $exception_model = self::save_exception($exception_name);

        $status_code = self::getCodePriority(exception: $exception,default_code: $exception_model?->status_code ?? null);
        $message = self::getMessagePriority(exception: $exception,default_message: $exception_model?->message ?? null);
        $errors = self::getErrorsPriority($exception);
        $extra_data = self::getExtraData($exception);

        return [
            'status_code' => $status_code,
            'message' => $message,
            'errors' => $errors,
            'extra_data' => $extra_data,
        ];
    }

    public function response(Request $request, $exception = null): Response|JsonResponse
    {
        $extract_data_exception = self::extract_data_exception($this);

        /**
         * @var int $status_code
         * @var string $message
         * @var array $errors
         * @var array $extra_data
         */
        extract($extract_data_exception);
        if ($request->wantsJson() || $request->ajax()) {
            return $this->responseJson(status_code: $status_code, message: $message, errors: $errors);
        }
        return $this->responseWebPage(status_code: $status_code, message: $message, errors: $errors);
    }

    public static function responseJson($status_code = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR, $message = '', $errors = [], $extra_data = []): JsonResponse
    {
        return ResponseHelper::responseDefault(data: $extra_data, message: $message, statusCode: $status_code, errors: $errors);
    }

    public static function responseWebPage($status_code = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR, $message = '', $errors = [], $extra_data = []): Response
    {
        return response()->view('exception::errors.custom', [
            'code' => $status_code,
            'message' => $message,
            'errors' => $errors,
            'extra_data' => $extra_data,
        ]);
    }
}
