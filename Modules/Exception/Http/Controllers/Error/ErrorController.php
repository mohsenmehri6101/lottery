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
