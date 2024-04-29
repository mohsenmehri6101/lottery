<?php

namespace Modules\Geographical\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Geographical\Http\Requests\Province\ProvinceIndexRequest;
use Modules\Geographical\Http\Requests\Province\ProvinceShowRequest;
use Modules\Geographical\Http\Requests\Province\ProvinceStoreRequest;
use Modules\Geographical\Http\Requests\Province\ProvinceUpdateRequest;
use Modules\Geographical\Services\ProvinceService;

class ProvinceController extends Controller
{
    public function __construct(public ProvinceService $provinceService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/provinces",
     *     tags={"provinces"},
     *     summary="لیست استان ها",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="integer"),description="name"),
     *     @OA\Parameter(name="is_center",in="query",required=false, @OA\Schema(type="integer"),description="is_center"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ProvinceIndexRequest $request): JsonResponse
    {
        $provinces = $this->provinceService->index($request);
        return ResponseHelper::responseSuccess(data: $provinces);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/provinces/{id}",
     *     tags={"provinces"},
     *     summary="نمایش یک استان",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ProvinceShowRequest $request, $province_id): JsonResponse
    {
        $province = $this->provinceService->show($request, $province_id);
        return $province ? ResponseHelper::responseSuccessShow(data: $province) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/provinces",
     *     tags={"provinces"},
     *     summary="ذخیره استان",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=true, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="type",in="query",required=false, @OA\Schema(type="integer"),description="type"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ProvinceStoreRequest $request): JsonResponse
    {
        $province = $this->provinceService->store($request);
        return $province ? ResponseHelper::responseSuccessStore(data: $province) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/provinces/{id}",
     *     tags={"provinces"},
     *     summary="ویرایش استان",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="province",in="query",required=false, @OA\Schema(type="string"),description="province"),
     *     @OA\Parameter(name="type",in="query",required=false, @OA\Schema(type="integer"),description="type"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ProvinceUpdateRequest $request, $province_id): JsonResponse
    {
        $province = $this->provinceService->update($request, $province_id);
        return $province ? ResponseHelper::responseSuccessUpdate(data: $province) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/provinces/{id}",
     *     tags={"provinces"},
     *     summary="حذف استان",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($province_id): JsonResponse
    {
        $status_delete = $this->provinceService->destroy($province_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
