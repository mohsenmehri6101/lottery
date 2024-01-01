<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Reserve\ReserveIndexRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveShowRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveStoreBlockRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveStoreRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveUpdateRequest;
use Modules\Gym\Http\Requests\Reserve\MyReserveRequest;
use Modules\Gym\Services\ReserveService;
use Illuminate\Http\JsonResponse;

class ReserveController extends Controller
{
    public function __construct(public ReserveService $reserveService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves",
     *     tags={"reserves"},
     *     summary="list reserves",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is:userCreator,userEditor,user,reserveTemplate,gym,factors"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ReserveIndexRequest $request): JsonResponse
    {
        $reserves = $this->reserveService->index($request);
        return ResponseHelper::responseSuccess(data: $reserves);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves/my-reserves",
     *     tags={"reserves"},
     *     summary="list my reserves (user logged in)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is: userCreator,userEditor,user,reserveTemplate,gym,factors"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myReserve(MyReserveRequest $request): JsonResponse
    {
        $reserves = $this->reserveService->myReserve($request);
        return ResponseHelper::responseSuccess(data: $reserves);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves/{id}",
     *     tags={"reserves"},
     *     summary="show reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ReserveShowRequest $request, $reserve_id): JsonResponse
    {
        $reserve = $this->reserveService->show($request, $reserve_id);
        return $reserve ? ResponseHelper::responseSuccessShow(data: $reserve) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reserves",
     *     tags={"reserves"},
     *     summary="save reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="reserve_template_id",in="query",required=true, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="user_id",in="query",required=true, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="dated_at",in="query",required=true, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ReserveStoreRequest $request): JsonResponse
    {
        $reserve = $this->reserveService->store($request);
        return $reserve ? ResponseHelper::responseSuccessStore(data: $reserve) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reserves/blocks",
     *     tags={"reserves"},
     *     summary="save reserve blocks",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="reserve_template_id",in="query",required=true, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="dated_at",in="query",required=true, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function storeBlocks(ReserveStoreBlockRequest $request): JsonResponse
    {
        $reserve = $this->reserveService->storeBlocks($request);
        return $reserve ? ResponseHelper::responseSuccessStore(data: $reserve) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reserves/{id}",
     *     tags={"reserves"},
     *     summary="update reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ReserveUpdateRequest $request, $reserve_id): JsonResponse
    {
        $reserve = $this->reserveService->update($request, $reserve_id);
        return $reserve ? ResponseHelper::responseSuccessUpdate(data: $reserve) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reserves/{id}",
     *     tags={"reserves"},
     *     summary="delete reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($reserve_id): JsonResponse
    {
        $status_delete = $this->reserveService->destroy($reserve_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
