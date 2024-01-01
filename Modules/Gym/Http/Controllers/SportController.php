<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Sport\SportIndexRequest;
use Modules\Gym\Http\Requests\Sport\SportShowRequest;
use Modules\Gym\Http\Requests\Sport\SportStoreRequest;
use Modules\Gym\Http\Requests\Sport\SportUpdateRequest;
use Modules\Gym\Http\Requests\Sport\DeleteSportToGymRequest;
use Modules\Gym\Http\Requests\Sport\SyncSportToGymRequest;
use Modules\Gym\Services\SportService;
use Illuminate\Http\JsonResponse;

class SportController extends Controller
{
    public function __construct(public SportService $sportService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/sports",
     *     tags={"sports"},
     *     summary="لیست انواع ورزش ها",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="slug",in="query",required=false, @OA\Schema(type="string"),description="slug"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(SportIndexRequest $request): JsonResponse
    {
        $sports = $this->sportService->index($request);
        return ResponseHelper::responseSuccess(data: $sports);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/sports/{id}",
     *     tags={"sports"},
     *     summary="نمایش تکی ورزش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(SportShowRequest $request, $sport_id): JsonResponse
    {
        $sport = $this->sportService->show($request, $sport_id);
        return $sport ? ResponseHelper::responseSuccessShow(data: $sport) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/sports",
     *     tags={"sports"},
     *     summary="ذخیره ورزش جدید",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(SportStoreRequest $request): JsonResponse
    {
        $sport = $this->sportService->store($request);
        return $sport ? ResponseHelper::responseSuccessStore(data: $sport) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/sports/{id}",
     *     tags={"sports"},
     *     summary="ویرایش ورزش جدید",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(SportUpdateRequest $request, $sport_id): JsonResponse
    {
        $sport = $this->sportService->update($request, $sport_id);
        return $sport ? ResponseHelper::responseSuccessUpdate(data: $sport) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/sports/{id}",
     *     tags={"sports"},
     *     summary="حذف ورزش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($sport_id): JsonResponse
    {
        $status_delete = $this->sportService->destroy($sport_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/sports/sync-sport-to-gym",
     *     tags={"sports"},
     *     summary="اتصال ورزش به باشگاه",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="sport_id",in="query",required=false, @OA\Schema(type="string"),description="sport_id"),
     *     @OA\Parameter(name="sports",in="query",required=false, @OA\Schema(type="string"),description="sports"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function syncSportToGym(SyncSportToGymRequest $request): JsonResponse
    {
        $status = $this->sportService->syncSportToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/sports/delete-sport-to-gym",
     *     tags={"sports"},
     *     summary="حذف اتصال ورزش به باشگاه",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="sport_id",in="query",required=false, @OA\Schema(type="string"),description="sport_id"),
     *     @OA\Parameter(name="sports",in="query",required=false, @OA\Schema(type="string"),description="sports"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteSportToGym(DeleteSportToGymRequest $request): JsonResponse
    {
        $status = $this->sportService->deleteSportToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }
}
