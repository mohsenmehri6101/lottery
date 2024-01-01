<?php

namespace Modules\Exception\Http\Controllers\Exception;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Exception\Http\Requests\ExceptionIndexRequest;
use Modules\Exception\Http\Requests\ExceptionShowRequest;
use Modules\Exception\Http\Requests\ExceptionStoreRequest;
use Modules\Exception\Http\Requests\ExceptionUpdateRequest;
use Modules\Exception\Services\ExceptionService;

class ExceptionController extends Controller
{
    public function __construct(public ExceptionService $exceptionService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/exception/exceptions",
     *     tags={"exceptions"},
     *     summary="list exceptions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="exception",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="message",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="level",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="status_code",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ExceptionIndexRequest $request): JsonResponse
    {
        $exceptions = $this->exceptionService->index($request);
        return ResponseHelper::responseSuccessIndex(data: $exceptions);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/exception/exceptions/{id}",
     *     tags={"exceptions"},
     *     summary="show exception",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ExceptionShowRequest $request, $exception_id)
    {
        $exception = $this->exceptionService->show($request, $exception_id);
        return $exception ? ResponseHelper::responseSuccessShow(data: $exception) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/exception/exceptions",
     *     tags={"exceptions"},
     *     summary="store exception",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="exception",in="query",required=false, @OA\Schema(type="string"),description="exception"),
     *     @OA\Parameter(name="message",in="query",required=false, @OA\Schema(type="string"),description="message"),
     *     @OA\Parameter(name="level",in="query",required=false, @OA\Schema(type="number"),description="level"),
     *     @OA\Parameter(name="status_code",in="query",required=false, @OA\Schema(type="number"),description="status_code"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ExceptionStoreRequest $request)
    {
        $exception = $this->exceptionService->store($request);
        return $exception ? ResponseHelper::responseSuccessStore(data: $exception) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/exception/exceptions/{id}",
     *     tags={"exceptions"},
     *     summary="update exception",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Parameter(name="exception",in="query",required=false, @OA\Schema(type="string"),description="exception"),
     *     @OA\Parameter(name="message",in="query",required=false, @OA\Schema(type="string"),description="message"),
     *     @OA\Parameter(name="level",in="query",required=false, @OA\Schema(type="number"),description="level"),
     *     @OA\Parameter(name="status_code",in="query",required=false, @OA\Schema(type="number"),description="status_code"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ExceptionUpdateRequest $request, $exception_id)
    {
        $exception = $this->exceptionService->update($request, $exception_id);
        return $exception ? ResponseHelper::responseSuccessUpdate(data: $exception) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/exception/exceptions/{id}",
     *     tags={"exceptions"},
     *     summary="delete exception",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($exception_id)
    {
        $status_delete = $this->exceptionService->destroy($exception_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

}
