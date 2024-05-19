<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Attribute\AttributeIndexRequest;
use Modules\Gym\Http\Requests\Attribute\AttributeShowRequest;
use Modules\Gym\Http\Requests\Attribute\AttributeStoreRequest;
use Modules\Gym\Http\Requests\Attribute\AttributeUpdateRequest;
use Modules\Gym\Http\Requests\Attribute\DeleteAttributeToGymRequest;
use Modules\Gym\Http\Requests\Attribute\SyncAttributeToGymRequest;
use Modules\Gym\Services\AttributeService;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
    public function __construct(public AttributeService $attributeService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/attributes",
     *     tags={"attributes"},
     *     summary="list attributes",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="slug",in="query",required=false, @OA\Schema(type="string"),description="slug"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(AttributeIndexRequest $request): JsonResponse
    {
        $attributes = $this->attributeService->index($request);
        return ResponseHelper::responseSuccess(data: $attributes);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/attributes/{id}",
     *     tags={"attributes"},
     *     summary="show attribute",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(AttributeShowRequest $request, $attribute_id): JsonResponse
    {
        $attribute = $this->attributeService->show($request, $attribute_id);
        return $attribute ? ResponseHelper::responseSuccessShow(data: $attribute) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/attributes",
     *     tags={"attributes"},
     *     summary="save attribute",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(AttributeStoreRequest $request): JsonResponse
    {
        $attribute = $this->attributeService->store($request);
        return $attribute ? ResponseHelper::responseSuccessStore(data: $attribute) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/attributes/{id}",
     *     tags={"attributes"},
     *     summary="update attribute",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(AttributeUpdateRequest $request, $attribute_id): JsonResponse
    {
        $attribute = $this->attributeService->update($request, $attribute_id);
        return $attribute ? ResponseHelper::responseSuccessUpdate(data: $attribute) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/attributes/{id}",
     *     tags={"attributes"},
     *     summary="delete attribute",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($attribute_id): JsonResponse
    {
        $status_delete = $this->attributeService->destroy($attribute_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/attributes/sync-attribute-to-gym",
     *     tags={"attributes"},
     *     summary="sync attribute to gym",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="attribute_id",in="query",required=false, @OA\Schema(type="string"),description="attribute_id"),
     *     @OA\Parameter(name="attributes",in="query",required=false, @OA\Schema(type="string"),description="attributes"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function syncAttributeToGym(SyncAttributeToGymRequest $request): JsonResponse
    {
        $status = $this->attributeService->syncAttributeToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/attributes/delete-attribute-to-gym",
     *     tags={"attributes"},
     *     summary="delete attribute to gym",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="attribute_id",in="query",required=false, @OA\Schema(type="string"),description="attribute_id"),
     *     @OA\Parameter(name="attributes",in="query",required=false, @OA\Schema(type="string"),description="attributes"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteAttributeToGym(DeleteAttributeToGymRequest $request): JsonResponse
    {
        $status = $this->attributeService->deleteAttributeToGym($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

}
