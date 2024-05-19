<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Tag\TagIndexRequest;
use Modules\Gym\Http\Requests\Tag\TagShowRequest;
use Modules\Gym\Http\Requests\Tag\TagStoreRequest;
use Modules\Gym\Http\Requests\Tag\TagUpdateRequest;
use Modules\Gym\Http\Requests\Tag\DeleteTagToGymRequest;
use Modules\Gym\Http\Requests\Tag\SyncTagToGymRequest;
use Modules\Gym\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function __construct(public TagService $tagService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags",
     *     tags={"tags"},
     *     summary="لیست برچسب ها",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="tag"),
     *     @OA\Parameter(name="slug",in="query",required=false, @OA\Schema(type="string"),description="slug"),
     *     @OA\Parameter(name="type",in="query",required=false, @OA\Schema(type="integer"),description="type"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(TagIndexRequest $request): JsonResponse
    {
        $tags = $this->tagService->index($request);
        return ResponseHelper::responseSuccess(data: $tags);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/{id}",
     *     tags={"tags"},
     *     summary="لیست تکی برچسب",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(TagShowRequest $request, $tag_id)
    {
        $tag = $this->tagService->show($request, $tag_id);
        return $tag ? ResponseHelper::responseSuccessShow(data: $tag) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tags",
     *     tags={"tags"},
     *     summary="ذخیره برچسب",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="tag",in="query",required=true, @OA\Schema(type="string"),description="tag"),
     *     @OA\Parameter(name="type",in="query",required=false, @OA\Schema(type="integer"),description="type"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(TagStoreRequest $request)
    {
        $tag = $this->tagService->store($request);
        return $tag ? ResponseHelper::responseSuccessStore(data: $tag) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tags/{id}",
     *     tags={"tags"},
     *     summary="ویرایش برچسب",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="tag"),
     *     @OA\Parameter(name="type",in="query",required=false, @OA\Schema(type="integer"),description="type"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(TagUpdateRequest $request, $tag_id)
    {
        $tag = $this->tagService->update($request, $tag_id);
        return $tag ? ResponseHelper::responseSuccessUpdate(data: $tag) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tags/{id}",
     *     tags={"tags"},
     *     summary="حذف برچسب",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($tag_id)
    {
        $status_delete = $this->tagService->destroy($tag_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/tags/sync-tag-to-gym",
     *     tags={"tags"},
     *     summary="اتصال برچسب به باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="tag_id",in="query",required=false, @OA\Schema(type="string"),description="tag_id"),
     *     @OA\Parameter(name="tags",in="query",required=false, @OA\Schema(type="string"),description="tags"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function syncTagToGym(SyncTagToGymRequest $request)
    {
        $status = $this->tagService->syncTagToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/tags/delete-tag-to-gym",
     *     tags={"tags"},
     *     summary="حذف اتصال برچسب به باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="tag_id",in="query",required=false, @OA\Schema(type="string"),description="tag_id"),
     *     @OA\Parameter(name="tags",in="query",required=false, @OA\Schema(type="string"),description="tags"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteTagToGym(DeleteTagToGymRequest $request)
    {
        $status = $this->tagService->deleteTagToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

}
