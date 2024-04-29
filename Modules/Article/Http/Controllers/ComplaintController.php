<?php

namespace Modules\Article\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Complaint\ComplaintIndexRequest;
use Modules\Article\Http\Requests\Complaint\ComplaintShowRequest;
use Modules\Article\Http\Requests\Complaint\ComplaintStoreRequest;
use Modules\Article\Http\Requests\Complaint\ComplaintUpdateRequest;
use Modules\Article\Services\ComplaintService;
use Illuminate\Http\JsonResponse;

class ComplaintController extends Controller
{
    public function __construct(public ComplaintService $complaintService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/complaints",
     *     tags={"complaints"},
     *     summary="list complaints",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="factor_id",in="query",required=false, @OA\Schema(type="integer"),description="factor_id"),
     *     @OA\Parameter(name="article_id",in="query",required=false, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="reserve_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="common_complaint_id",in="query",required=false, @OA\Schema(type="integer"),description="common_complaint_id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:list is:user,userCreator,userEditor,factor,article,reserve,reserveTemplate"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ComplaintIndexRequest $request): JsonResponse
    {
        $complaints = $this->complaintService->index($request);
        return ResponseHelper::responseSuccess(data: $complaints);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/complaints/{id}",
     *     tags={"complaints"},
     *     summary="show complaint",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:list is:user,userCreator,userEditor,factor,article,reserve,reserveTemplate"),     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ComplaintShowRequest $request, $complaint_id): JsonResponse
    {
        $complaint = $this->complaintService->show($request, $complaint_id);
        return $complaint ? ResponseHelper::responseSuccessShow(data: $complaint) : ResponseHelper::responseFailedShow();
    }


    /**
     * @OA\Post(
     *     path="/api/v1/complaints",
     *     tags={"complaints"},
     *     summary="save complaint",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="user_id",in="query",required=true, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="description",in="query",required=true, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="status",in="query",required=true, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="factor_id",in="query",required=true, @OA\Schema(type="integer"),description="factor_id"),
     *     @OA\Parameter(name="article_id",in="query",required=true, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="reserve_id",in="query",required=true, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=true, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="common_complaint_id",in="query",required=false, @OA\Schema(type="integer"),description="common_complaint_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ComplaintStoreRequest $request): JsonResponse
    {
        $complaint = $this->complaintService->store($request);
        return $complaint ? ResponseHelper::responseSuccessStore(data: $complaint) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/complaints/{id}",
     *     tags={"complaints"},
     *     summary="update complaint",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="factor_id",in="query",required=false, @OA\Schema(type="integer"),description="factor_id"),
     *     @OA\Parameter(name="article_id",in="query",required=false, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="reserve_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="common_complaint_id",in="query",required=false, @OA\Schema(type="integer"),description="common_complaint_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ComplaintUpdateRequest $request, $complaint_id): JsonResponse
    {
        $complaint = $this->complaintService->update($request, $complaint_id);
        return $complaint ? ResponseHelper::responseSuccessUpdate(data: $complaint) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/complaints/{id}",
     *     tags={"complaints"},
     *     summary="delete complaint",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($complaint_id): JsonResponse
    {
        $status_delete = $this->complaintService->destroy($complaint_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
