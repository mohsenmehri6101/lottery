<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Category\CategoryIndexRequest;
use Modules\Gym\Http\Requests\Category\CategoryShowRequest;
use Modules\Gym\Http\Requests\Category\CategoryStoreRequest;
use Modules\Gym\Http\Requests\Category\CategoryUpdateRequest;
use Modules\Gym\Http\Requests\Category\DeleteCategoryToGymRequest;
use Modules\Gym\Http\Requests\Category\SyncCategoryToGymRequest;
use Modules\Gym\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"categories"},
     *     summary="لیست دسته بندی ها",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false,@OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false,@OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false,@OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false,@OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false,@OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="search",in="query",required=false,@OA\Schema(type="string"),description="search"),
     *     @OA\Parameter(name="slug",in="query",required=false,@OA\Schema(type="string"),description="slug"),
     *     @OA\Parameter(name="parent",in="query",required=false,@OA\Schema(type="integer"),description="parent"),
     *     @OA\Parameter(name="withs",in="query",required=false,@OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="order_by",in="query",required=false,@OA\Schema(type="string"),description="Column to sort by"),
     *     @OA\Parameter(name="direction_by",in="query",required=false,@OA\Schema(type="string", enum={"asc", "desc"}),description="Sort direction (asc or desc)"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function index(CategoryIndexRequest $request): JsonResponse
    {
        $categorys = $this->categoryService->index($request);
        return ResponseHelper::responseSuccess(data: $categorys);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     tags={"categories"},
     *     summary="نمایش تکی دسته بندی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(CategoryShowRequest $request, $category_id): JsonResponse
    {
        $category = $this->categoryService->show($request, $category_id);
        return $category ? ResponseHelper::responseSuccessShow(data: $category) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     tags={"categories"},
     *     summary="ذخیره دسته بندی جدید",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = $this->categoryService->store($request);
        return $category ? ResponseHelper::responseSuccessStore(data: $category) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     tags={"categories"},
     *     summary="ویرایش دسته بندی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(CategoryUpdateRequest $request, $category_id): JsonResponse
    {
        $category = $this->categoryService->update($request, $category_id);
        return $category ? ResponseHelper::responseSuccessUpdate(data: $category) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     tags={"categories"},
     *     summary="حذف دسته بندی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($category_id): JsonResponse
    {
        $status_delete = $this->categoryService->destroy($category_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/categories/sync-category-to-gym",
     *     tags={"categories"},
     *     summary="اتصال دسته بندی به باشگاه",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="category_id",in="query",required=false, @OA\Schema(type="string"),description="category_id"),
     *     @OA\Parameter(name="categories",in="query",required=false, @OA\Schema(type="string"),description="categories"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function syncCategoryToGym(SyncCategoryToGymRequest $request): JsonResponse
    {
        $status = $this->categoryService->syncCategoryToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/categories/delete-category-to-gym",
     *     tags={"categories"},
     *     summary="حذف اتصال دسته بندی به باشگاه",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="category_id",in="query",required=false, @OA\Schema(type="string"),description="category_id"),
     *     @OA\Parameter(name="categories",in="query",required=false, @OA\Schema(type="string"),description="categories"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteCategoryToGym(DeleteCategoryToGymRequest $request): JsonResponse
    {
        $status = $this->categoryService->deleteCategoryToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }
}
