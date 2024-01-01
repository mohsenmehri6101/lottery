<?php

namespace Modules\Exception\Http\Controllers\Error;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Exception\Http\Requests\Error\ErrorIndexRequest;
use Modules\Exception\Http\Requests\Error\ErrorStoreRequest;
use Modules\Exception\Http\Requests\Error\ErrorUpdateRequest;
use Modules\Exception\Services\ErrorService;

class ErrorController extends Controller
{
    public function __construct(public ErrorService $errorService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/exception/errors",
     *     tags={"exceptions"},
     *     summary="list exceptions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="exception",in="query",required=false, @OA\Schema(type="string"),description="exception"),
     *     @OA\Parameter(name="message",in="query",required=false, @OA\Schema(type="string"),description="message"),
     *     @OA\Parameter(name="level",in="query",required=false, @OA\Schema(type="number"),description="level"),
     *     @OA\Parameter(name="status_code",in="query",required=false, @OA\Schema(type="number"),description="status_code"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ErrorIndexRequest $request): JsonResponse
    {
        $errors = $this->errorService->index($request);
        return $errors ? ResponseHelper::responseSuccessIndex(data: $errors) : ResponseHelper::responseFailedIndex();
    }

    public function show($error_id): JsonResponse
    {
        $error = $this->errorService->show($error_id);
        return $error ? ResponseHelper::responseSuccessShow(data: $error) : ResponseHelper::responseFailedShow();
    }

    public function store(ErrorStoreRequest $request)
    {
        $error = $this->errorService->store($request);
        return $error ? ResponseHelper::responseSuccessStore(data: ['error' => $error]) : ResponseHelper::responseFailedStore();
    }

    public function update(ErrorUpdateRequest $request, $error_id)
    {
        $status_update = $this->errorService->update($request, $error_id);
        return $status_update ? ResponseHelper::responseSuccessUpdate() : ResponseHelper::responseFailedUpdate();
    }

    public function destroy($error_id)
    {
        $status_delete = $this->errorService->destroy($error_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

}
