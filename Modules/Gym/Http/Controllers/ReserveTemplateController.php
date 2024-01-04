<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateBetweenDateRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateIndexRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateShowRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateStoreRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateUpdateRequest;
use Modules\Gym\Services\ReserveTemplateService;
use Illuminate\Http\JsonResponse;

class ReserveTemplateController extends Controller
{
    public function __construct(public ReserveTemplateService $reserveTemplateService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserve_templates",
     *     tags={"reserve_templates"},
     *     summary="list reserve_templates",
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="from",in="query",required=false, @OA\Schema(type="string"),description="from"),
     *     @OA\Parameter(name="to",in="query",required=false, @OA\Schema(type="string"),description="to"),
     *     @OA\Parameter(name="gym_id",in="query",required=false, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="week_number",in="query",required=false, @OA\Schema(type="integer"),description="week_number"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="cod",in="query",required=false, @OA\Schema(type="integer"),description="cod"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is: userCreator,userEditor,user,gym"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ReserveTemplateIndexRequest $request): JsonResponse
    {
        $reserve_templates = $this->reserveTemplateService->index($request);
        return ResponseHelper::responseSuccess(data: $reserve_templates);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserve_templates/{id}",
     *     tags={"reserve_templates"},
     *     summary="show reserve_template",
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is:userCreator,userEditor,user,gym"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ReserveTemplateShowRequest $request, $reserve_template_id): JsonResponse
    {
        $reserve_template = $this->reserveTemplateService->show($request, $reserve_template_id);
        return $reserve_template ? ResponseHelper::responseSuccessShow(data: $reserve_template) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reserve_templates",
     *     tags={"reserve_templates"},
     *     summary="save reserve_template",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="from",in="query",required=true, @OA\Schema(type="string"),description="from"),
     *     @OA\Parameter(name="to",in="query",required=true, @OA\Schema(type="string"),description="to"),
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="week_number",in="query",required=true, @OA\Schema(type="integer"),description="week_number"),
     *     @OA\Parameter(name="price",in="query",required=true, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="cod",in="query",required=false, @OA\Schema(type="integer"),description="cod"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ReserveTemplateStoreRequest $request): JsonResponse
    {
        $reserve_template = $this->reserveTemplateService->store($request);
        return $reserve_template ? ResponseHelper::responseSuccessStore(data: $reserve_template) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reserve_templates/{id}",
     *     tags={"reserve_templates"},
     *     summary="update reserve_template",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="from",in="query",required=false, @OA\Schema(type="string"),description="from"),
     *     @OA\Parameter(name="to",in="query",required=false, @OA\Schema(type="string"),description="to"),
     *     @OA\Parameter(name="gym_id",in="query",required=false, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="week_number",in="query",required=false, @OA\Schema(type="integer"),description="week_number"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="cod",in="query",required=false, @OA\Schema(type="integer"),description="cod"),     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ReserveTemplateUpdateRequest $request, $reserve_template_id): JsonResponse
    {
        $reserve_template = $this->reserveTemplateService->update($request, $reserve_template_id);
        return $reserve_template ? ResponseHelper::responseSuccessUpdate(data: $reserve_template) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reserve_templates/{id}",
     *     tags={"reserve_templates"},
     *     summary="delete reserve_template",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($reserve_template_id): JsonResponse
    {
        $status_delete = $this->reserveTemplateService->destroy($reserve_template_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserve_templates/between-date",
     *     tags={"reserve_templates"},
     *     summary="Retrieve reserve templates between dates",
     *     @OA\Parameter(name="from", in="query", required=true, @OA\Schema(type="string"), description="Start date"),
     *     @OA\Parameter(name="to", in="query", required=true, @OA\Schema(type="string"), description="End date"),
     *     @OA\Parameter(name="gym_id", in="query", required=true, @OA\Schema(type="integer"), description="Gym ID"),
     *     @OA\Parameter(name="order_by", in="query", required=true, @OA\Schema(type="string"), description="order_by"),
     *     @OA\Parameter(name="direction_by", in="query", required=true, @OA\Schema(type="string"), description="direction_by"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function betweenDate(ReserveTemplateBetweenDateRequest $request): JsonResponse
    {
        $reserve_templates = $this->reserveTemplateService->betweenDate($request);
        return ResponseHelper::responseSuccess(data: $reserve_templates);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserve_templates/gender-acceptances",
     *     tags={"reserve_templates"},
     *     summary="list gender-acceptance reserve_templates",
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function gender_acceptances(Request $request): JsonResponse
    {
        $gender_acceptances = $this->reserveTemplateService->gender_acceptances($request);
        $gender_acceptances = collect($gender_acceptances)->map(function ($name, $id) {
            return ["id" => $id, "name" => $name];
        });
        return ResponseHelper::responseSuccess(data: $gender_acceptances);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/reserve_templates/statuses",
     *     tags={"reserve_templates"},
     *     summary="list statuses reserve_templates",
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function statuses(Request $request): JsonResponse
    {
        $statuses = $this->reserveTemplateService->statuses($request);
        $statuses = collect($statuses)->map(function ($name, $id) {
            return ["id" => $id, "name" => $name];
        });
        return ResponseHelper::responseSuccess(data: $statuses);
    }
}
