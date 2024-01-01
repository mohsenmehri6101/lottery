<?php

namespace Modules\Geographical\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Geographical\Http\Requests\City\CityIndexRequest;
use Modules\Geographical\Http\Requests\City\CityShowRequest;
use Modules\Geographical\Http\Requests\City\CityStoreRequest;
use Modules\Geographical\Http\Requests\City\CityUpdateRequest;
use Modules\Geographical\Services\CityService;

class CityController extends Controller
{
    public function __construct(public CityService $cityService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/cities",
     *     tags={"cities"},
     *     summary="لیست شهرها",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="is_center",in="query",required=false, @OA\Schema(type="integer"),description="is_center"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(CityIndexRequest $request): JsonResponse
    {
        $cities = $this->cityService->index($request);
        return ResponseHelper::responseSuccess(data: $cities);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/cities/{id}",
     *     tags={"cities"},
     *     summary="نمایش یک شهر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(CityShowRequest $request, $city_id): JsonResponse
    {
        $city = $this->cityService->show($request, $city_id);
        return $city ? ResponseHelper::responseSuccessShow(data: $city) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/cities",
     *     tags={"cities"},
     *     summary="ذخیره شهر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="city",in="query",required=true, @OA\Schema(type="string"),description="city"),
     *     @OA\Parameter(name="type",in="query",required=false, @OA\Schema(type="integer"),description="type"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(CityStoreRequest $request): JsonResponse
    {
        $city = $this->cityService->store($request);
        return $city ? ResponseHelper::responseSuccessStore(data: $city) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/cities/{id}",
     *     tags={"cities"},
     *     summary="ویرایش شهر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="city",in="query",required=false, @OA\Schema(type="string"),description="city"),
     *     @OA\Parameter(name="type",in="query",required=false, @OA\Schema(type="integer"),description="type"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(CityUpdateRequest $request, $city_id): JsonResponse
    {
        $city = $this->cityService->update($request, $city_id);
        return $city ? ResponseHelper::responseSuccessUpdate(data: $city) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cities/{id}",
     *     tags={"cities"},
     *     summary="حذف شهر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($city_id): JsonResponse
    {
        $status_delete = $this->cityService->destroy($city_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
