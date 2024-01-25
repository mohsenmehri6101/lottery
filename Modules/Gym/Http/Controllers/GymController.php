<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Gym\DeleteImageGymRequest;
use Modules\Gym\Http\Requests\Gym\GetInitializeRequestsSelectors;
use Modules\Gym\Http\Requests\Gym\GymIndexRequest;
use Modules\Gym\Http\Requests\Gym\GymLikeRequest;
use Modules\Gym\Http\Requests\Gym\GymShowRequest;
use Modules\Gym\Http\Requests\Gym\GymStoreRequest;
use Modules\Gym\Http\Requests\Gym\GymUpdateRequest;
use Modules\Gym\Http\Requests\Gym\MyGymsRequest;
use Modules\Gym\Services\GymService;
use Illuminate\Http\JsonResponse;

class GymController extends Controller
{
    public function __construct(public GymService $gymService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/gyms",
     *     tags={"gyms"},
     *     summary="لیست باشگاه های ورزشی",
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="search",in="query",required=false, @OA\Schema(type="string"),description="search"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="min_price",in="query",required=false, @OA\Schema(type="string"),description="min_price"),
     *     @OA\Parameter(name="max_price",in="query",required=false, @OA\Schema(type="string"),description="max_price"),
     *     @OA\Parameter(name="latitude",in="query",required=false, @OA\Schema(type="string"),description="latitude"),
     *     @OA\Parameter(name="longitude",in="query",required=false, @OA\Schema(type="string"),description="longitude"),
     *     @OA\Parameter(name="city_id",in="query",required=false, @OA\Schema(type="string"),description="city_id"),
     *     @OA\Parameter(name="like_count",in="query",required=false, @OA\Schema(type="integer"),description="like_count"),
     *     @OA\Parameter(name="dislike_count",in="query",required=false, @OA\Schema(type="integer"),description="dislike_count"),
     *     @OA\Parameter(name="score",in="query",required=false, @OA\Schema(type="integer"),description="score"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="integer"),description="profit_share_percentage"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is:userCreator, userEditor, city, scores, keywords, categories, images, urlImages, tags,sports, attributes, reserveTemplates, reserves"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(GymIndexRequest $request): JsonResponse
    {
        $gyms = $this->gymService->index($request);
        return ResponseHelper::responseSuccessIndex(data: $gyms);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/gyms/my-gyms",
     *     tags={"gyms"},
     *     summary="لیست باشگاه های ورزشی من((برای مسئول سالن ورزشی)",
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="search",in="query",required=false, @OA\Schema(type="string"),description="search"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="min_price",in="query",required=false, @OA\Schema(type="string"),description="min_price"),
     *     @OA\Parameter(name="max_price",in="query",required=false, @OA\Schema(type="string"),description="max_price"),
     *     @OA\Parameter(name="latitude",in="query",required=false, @OA\Schema(type="string"),description="latitude"),
     *     @OA\Parameter(name="longitude",in="query",required=false, @OA\Schema(type="string"),description="longitude"),
     *     @OA\Parameter(name="city_id",in="query",required=false, @OA\Schema(type="string"),description="city_id"),
     *     @OA\Parameter(name="like_count",in="query",required=false, @OA\Schema(type="integer"),description="like_count"),
     *     @OA\Parameter(name="dislike_count",in="query",required=false, @OA\Schema(type="integer"),description="dislike_count"),
     *     @OA\Parameter(name="score",in="query",required=false, @OA\Schema(type="integer"),description="score"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="integer"),description="profit_share_percentage"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myGyms(MyGymsRequest $request): JsonResponse
    {
        $gyms = $this->gymService->myGyms($request);
        return ResponseHelper::responseSuccessIndex(data: $gyms);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/gyms/{id}",
     *     tags={"gyms"},
     *     summary="نمایش تکی باشگاه ورزشی",
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(GymShowRequest $request, $gym_id): JsonResponse
    {
        $gym = $this->gymService->show($request, $gym_id);
        return $gym ? ResponseHelper::responseSuccessShow(data: $gym) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/gyms",
     *     tags={"gyms"},
     *     summary="ذخیره باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=true, @OA\Schema(type="string"),description="Gym name"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="Gym description"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="Gym price"),
     *     @OA\Parameter(name="city_id",in="query",required=true, @OA\Schema(type="integer"),description="City ID"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="Gym user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="Gym status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="integer"),description="profit_share_percentage"),
     *     @OA\Parameter(name="tag_id",in="query",required=false, @OA\Schema(type="integer"),description="Tag ID"),
     *     @OA\Parameter(name="tags",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of tag IDs"),
     *     @OA\Parameter(name="category_id",in="query",required=false, @OA\Schema(type="integer"),description="Category ID"),
     *     @OA\Parameter(name="categories",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of category IDs"),
     *     @OA\Parameter(name="keyword_id",in="query",required=false, @OA\Schema(type="integer"),description="Keyword ID"),
     *     @OA\Parameter(name="keywords",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of keyword IDs"),
     *     @OA\Parameter(name="sport_id",in="query",required=false, @OA\Schema(type="integer"),description="Sport ID"),
     *     @OA\Parameter(name="sports",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of sport IDs"),
     *     @OA\Parameter(name="attribute_id",in="query",required=false, @OA\Schema(type="integer"),description="Attribute ID"),
     *     @OA\Parameter(name="attributes",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of attribute IDs"),
     *     @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"time_template"},
     *              @OA\Property(property="images", type="array", @OA\Items(type="file", format="binary"), description="Array of image files"),
     *              @OA\Property(property="time_template", type="array", @OA\Items(
     *              @OA\Property(property="from", type="string", format="H:i", description="Start time"),
     *              @OA\Property(property="to", type="string", format="H:i", description="End time"),
     *              @OA\Property(property="break_time", type="number", format="float", description="Break time in hours"),
     *              @OA\Property(property="price", type="number", format="float", description="Price"),
     *              @OA\Property(property="gender_acceptance", type="number", description="Gender acceptance status"),
     *              @OA\Property(property="week_numbers", type="array", @OA\Items(type="integer", format="int32"), description="Array of week numbers [1,2,3,4,5,6,7]"),
     *              ), description="Array of reserve template data"),
     *          )
     *      )
     *  ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={""},
     *                 @OA\Property(property="images", type="array", @OA\Items(type="file", format="binary"), description="Array of image files")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function store(GymStoreRequest $request): JsonResponse
    {
        $gym = $this->gymService->store($request);
        return $gym ? ResponseHelper::responseSuccessStore(data: $gym) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/gyms/like",
     *     tags={"gyms"},
     *     summary="لایک/دیسلایک باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="type",in="query",required=true, @OA\Schema(type="string"),description="type:like,dislike"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function like(GymLikeRequest $request): JsonResponse
    {
        $likes_count = $this->gymService->like($request);
        return ResponseHelper::responseSuccess(data: ['likes_count' => $likes_count]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/gyms/{id}",
     *     tags={"gyms"},
     *     summary="ویرایش باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=true, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="city_id",in="query",required=true, @OA\Schema(type="string"),description="city_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="string"),description="profit_share_percentage"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="user_id"),
     *     @OA\Parameter(name="tag_id",in="query",required=false, @OA\Schema(type="integer"),description="Tag ID"),
     *     @OA\Parameter(name="tags",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of tag IDs"),
     *     @OA\Parameter(name="category_id",in="query",required=false, @OA\Schema(type="integer"),description="Category ID"),
     *     @OA\Parameter(name="categories",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of category IDs"),
     *     @OA\Parameter(name="keyword_id",in="query",required=false, @OA\Schema(type="integer"),description="Keyword ID"),
     *     @OA\Parameter(name="keywords",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of keyword IDs"),
     *     @OA\Parameter(name="sport_id",in="query",required=false, @OA\Schema(type="integer"),description="Sport ID"),
     *     @OA\Parameter(name="sports",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of sport IDs"),
     *     @OA\Parameter(name="attribute_id",in="query",required=false, @OA\Schema(type="integer"),description="Attribute ID"),
     *     @OA\Parameter(name="attributes",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of attribute IDs"),
     *     @OA\Parameter(name="images",in="query",required=false, @OA\Schema(type="string"),description="images"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(GymUpdateRequest $request, $gym_id): JsonResponse
    {
        $gym = $this->gymService->update($request, $gym_id);
        return $gym ? ResponseHelper::responseSuccessUpdate(data: $gym) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/gyms/{id}",
     *     tags={"gyms"},
     *     summary="حذف باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($gym_id): JsonResponse
    {
        $status_delete = $this->gymService->destroy($gym_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/gyms/delete-image/{id}",
     *     tags={"gyms"},
     *     summary="حذف عکس باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="gym id"),
     *     @OA\Parameter(name="images",in="query",required=false, @OA\Schema(type="string"),description="image ids"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteImage(DeleteImageGymRequest $request, $gym_id): JsonResponse
    {
        $status_delete = $this->gymService->deleteImage($request, $gym_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/gyms/gym-status",
     *     tags={"gyms"},
     *     summary="لیست وضعیت های مختلف مختلف باشگاه ورزشی",
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function gymsStatus(Request $request): JsonResponse
    {
        $gyms_status = $this->gymService->gymStatus($request);
        $gyms_status = collect($gyms_status)->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        });
        return ResponseHelper::responseSuccess(data: $gyms_status);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/get-initialize-requests-selectors",
     *     tags={"gyms"},
     *     summary="Get initialization data for selectors",
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:gyms,tags,categories,sports,attributes,keywords,cities,provinces,gender_acceptances"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function getInitializeRequestsSelectors(GetInitializeRequestsSelectors $request): JsonResponse
    {
        $lists = $this->gymService->getInitializeRequestsSelectors($request);
        return ResponseHelper::responseSuccess(data: $lists);
    }

}
