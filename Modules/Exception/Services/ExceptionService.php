<?php

namespace Modules\Exception\Services;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Exception\Entities\Error;
use Modules\Exception\Http\Repositories\ExceptionRepository;
use Modules\Exception\Http\Requests\ExceptionIndexRequest;
use Modules\Exception\Http\Requests\ExceptionShowRequest;
use Modules\Exception\Http\Requests\ExceptionStoreRequest;
use Modules\Exception\Http\Requests\ExceptionUpdateRequest;
use Throwable;
use function class_basename;
use function dd;
use function exception_response_exception;
use function request;
use function set_user_creator;

class ExceptionService extends Controller
{
    use ExceptionServiceTrait;

    public mixed $app_debug = null;
    public mixed $app_env = null;
    const app_env_production = 'production';
    const app_env_local = 'local';
    const app_env_develop = 'develop';
    const app_env_test = 'test';

    public function __construct(public ExceptionRepository $exceptionRepository)
    {
        $this->app_debug = env('APP_DEBUG', false);
        $this->app_env = env('APP_ENV', self::app_env_production);
    }

    private static function insert_error($exception_model = null, $exception = null)
    {
        if (isset($exception_model) && $exception_model && $exception_model?->level >= config('config.min_level_exception_error', 0)) {
            $message = method_exists($exception, 'getMessage') ? $exception->getMessage() : null;
            $status_code = method_exists($exception, 'getCode') ? (int)$exception->getCode() : null;
            $status_code = $status_code == 0 ? $exception_model?->status_code : $status_code;
            $exception_name = class_basename($exception) ?? null;
            $extra_data = property_exists($exception, 'extra_data') ? $exception?->extra_data : [];
            $user_agent = request()->userAgent() ?? null;
            $requests = request()->all() ?? null;
            $headers = request()->headers->all() ?? null;
            $url = url()->current() ?? null;
            $stack_trace = $exception?->getMessage() . ':' . $exception?->getFile() . ':' . $exception?->getLine();

            Error::query()->create([
                'url' => $url ?? null,
                'status_code' => $status_code ?? null,
                'exception' => $exception_name ?? null,
                'message' => $message ?? null,
                'user_creator' => set_user_creator() ?? null,
                'stack_trace' => $stack_trace ?? null,
                'requests' => $requests ?? null,
                'headers' => $headers ?? null,
                'user_agent' => $user_agent ?? null,
                'extra_date' => $extra_data ?? null,
            ]);
        }
    }

    public static function reporter(Throwable $exception)
    {
        try {
            $exception_name = class_basename($exception);
            $exception_model = self::save_exception($exception_name);
            self::insert_error($exception_model, $exception);
        } catch (Exception $exception) {
            $app_env = env('APP_ENV', self::app_env_production);
            if ($app_env !== self::app_env_production) {
                dd('okey doke reporter',$exception->getMessage(),$exception->getFile(),$exception->getLine(),$exception->getCode());
            }
        }
    }

    public static function render($request, Throwable $exception)
    {
        try {
            return exception_response_exception($request, $exception);
        } catch (Exception $exception_) {
            $app_env = env('APP_ENV', self::app_env_production);
            if ($app_env !== self::app_env_production) {
                dd('okey doke render',$exception_->getMessage(),$exception_->getFile(),$exception_->getLine(),$exception_->getCode());
            }
        }
    }

    public function index(ExceptionIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $userStoreRequest = new ExceptionIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            return $this->exceptionRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ExceptionShowRequest $request, $exception_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->exceptionRepository->withRelations(relations: $withs)->findOrFail($exception_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ExceptionStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $exception = $this->exceptionRepository->create($fields);
            DB::commit();
            return $exception;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ExceptionUpdateRequest $request, $exception_id)
    {
        DB::beginTransaction();
        try {
            /** @var Exception $exception */
            $exception = $this->exceptionRepository->findOrFail($exception_id);
            $fields = $request->validated();
            $this->exceptionRepository->update($exception, $fields);
            DB::commit();
            return $this->exceptionRepository->findOrFail($exception_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($exception_id)
    {
        DB::beginTransaction();
        try {
            # find exception
            /** @var Exception $exception */
            $exception = $this->exceptionRepository->findOrFail($exception_id);

            # delete exception
            $status_delete_exception = $this->exceptionRepository->delete($exception);

            DB::commit();
            return $status_delete_exception;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
