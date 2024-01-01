<?php

namespace Modules\Exception\Http\Controllers\Error;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Exception\Http\Requests\Error\ErrorIndexRequest;
use Modules\Exception\Http\Requests\Error\ErrorShowRequest;
use Modules\Exception\Services\ErrorService;

class ErrorController extends Controller
{
    public function __construct(public ErrorService $errorService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/exception/errors",
     *     tags={"exceptions-errors"},
     *     summary="list exceptions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="integer"),description="paginate"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="integer"),description="page"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="integer"),description="per_page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="url",in="query",required=false, @OA\Schema(type="integer"),description="url"),
     *     @OA\Parameter(name="status_code",in="query",required=false, @OA\Schema(type="integer"),description="status_code"),
     *     @OA\Parameter(name="exception",in="query",required=false, @OA\Schema(type="integer"),description="exception"),
     *     @OA\Parameter(name="message",in="query",required=false, @OA\Schema(type="integer"),description="message"),
     *     @OA\Parameter(name="selects",in="query",required=false, @OA\Schema(type="string"),description="selects: id, url, status_code, exception, message, user_creator, stack_trace, requests, headers, user_agent, extra_date, created_at, updated_at, deleted_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="integer"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="integer"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="integer"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ErrorIndexRequest $request): JsonResponse
    {
        $errors = $this->errorService->index($request);
        return $errors ? ResponseHelper::responseSuccessIndex(data: $errors) : ResponseHelper::responseFailedIndex();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/exception/errors/{id}",
     *     tags={"exceptions-errors"},
     *     summary="show one error",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ErrorShowRequest $request,$error_id): JsonResponse
    {
        $error = $this->errorService->show($error_id);
        return $error ? ResponseHelper::responseSuccessShow(data: $error) : ResponseHelper::responseFailedShow();
    }

}
