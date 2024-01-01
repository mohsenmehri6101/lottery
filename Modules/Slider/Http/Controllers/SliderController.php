<?php

namespace Modules\Slider\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Slider\Http\Requests\Slider\SliderIndexRequest;
use Modules\Slider\Http\Requests\Slider\SliderShowRequest;
use Modules\Slider\Http\Requests\Slider\SliderStoreRequest;
use Modules\Slider\Http\Requests\Slider\SliderUpdateRequest;
use Modules\Slider\Services\SliderService;

class SliderController extends Controller
{

    /**
     * @param SliderService $sliderService
     */
    public function __construct(public SliderService $sliderService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/sliders",
     *     tags={"sliders"},
     *     summary="لیست اسلایدرها",
     *     description="لیست تمامی اسلایدرها سامانه",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="string"),description="شناسه جدول اسلایدر"),
     *     @OA\Parameter(name="title",in="query",required=false, @OA\Schema(type="string"),description="عنوان"),
     *     @OA\Parameter(name="link",in="query",required=false, @OA\Schema(type="string"),description="لینک"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="وضعیت اسلایدر"),
     *     @OA\Parameter(name="city_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه شهرستان"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="string"),description="کاربر ایجاد کننده"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="string"),description="کاربر ایجاد کننده"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs availables:image,advertise,province,town"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="تاریخ ایجاد"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="تاریخ ویرایش"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="تاریخ حذف"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function index(SliderIndexRequest $request): JsonResponse
    {
        $sliders = $this->sliderService->index($request);
        return ResponseHelper::responseSuccess($sliders);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/sliders/{id}",
     *     tags={"sliders"},
     *      summary="نمایش اسلایدر",
     *      description="نمایش تکی اسلایدر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="شناسه اسلایدر"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs availables:image,advertise,province,town"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function show(SliderShowRequest $request, $slider_id): JsonResponse
    {
        $slider = $this->sliderService->show($request, $slider_id);
        return $slider ? ResponseHelper::responseSuccessShow($slider) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/sliders/",
     *     tags={"sliders"},
     *     summary="ذخیره اسلایدر",
     *     description="ذخیره اسلایدر",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(required={},
     *                  @OA\Property(property="title", type="string", description="عنوان"),
     *                  @OA\Property(property="link", type="string", description="لینک"),
     *                  @OA\Property(property="status", type="string", description="وضعیت اسلایدر"),
     *                  @OA\Property(property="city_id", type="string", description="شناسه شهرستان"),
     *                  @OA\Property(property="image",type="string", format="binary", description="عکس"),
     *              )
     *          )
     *      ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function store(SliderStoreRequest $request): JsonResponse
    {
        $slider = $this->sliderService->store($request);
        return $slider ? ResponseHelper::responseSuccessStore($slider) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/sliders/{id}",
     *     tags={"sliders"},
     *     summary="ویرایش اسلایدر",
     *     description="ویرایش اسلایدر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="شناسه اسلایدر"),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(required={},
     *                  @OA\Property(property="title", type="string", description="عنوان"),
     *                  @OA\Property(property="link", type="string", description="لینک"),
     *                  @OA\Property(property="status", type="string", description="وضعیت اسلایدر"),
     *                  @OA\Property(property="city_id", type="string", description="شناسه استان"),
     *                  @OA\Property(property="image",type="string", format="binary", description="عکس"),
     *              )
     *          )
     *      ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function update(SliderUpdateRequest $request, $slider_id): JsonResponse
    {
        $slider = $this->sliderService->update($request, $slider_id);
        return $slider ? ResponseHelper::responseSuccessUpdate($slider) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/sliders/{id}",
     *     tags={"sliders"},
     *      summary="حذف اسلایدر",
     *      description="حذف اسلایدر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="شناسه اسلایدر"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function destroy($slider_id): JsonResponse
    {
        return $this->sliderService->destroy($slider_id) ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Get  (
     *     path="/api/v1/sliders/list-status-slider",
     *     tags={"sliders"},
     *      summary="لیست وضعیت های مجاز (جنسیت) اسلایدر",
     *      description="لیست وضعیت های مجاز (جنسیت) اسلایدر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="status",in="path",required=false, @OA\Schema(type="string"),description="وضعیت(شناسه یا متن)"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function listStatusSlider($status = null): JsonResponse
    {
        $status_sliders = $this->sliderService->listStatusSlider($status);
        $status_sliders = collect($status_sliders)->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        });
        return ResponseHelper::responseSuccess($status_sliders);
    }

}
