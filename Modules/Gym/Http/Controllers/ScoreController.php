<?php


namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Score\ScoreIndexRequest;
use Modules\Gym\Http\Requests\Score\ScoreShowRequest;
use Modules\Gym\Http\Requests\Score\ScoreStoreRequest;
use Modules\Gym\Http\Requests\Score\ScoreUpdateRequest;
use Modules\Gym\Services\ScoreService;
use Illuminate\Http\JsonResponse;

class ScoreController extends Controller
{
    public function __construct(public ScoreService $scoreService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/scores",
     *     tags={"scores"},
     *     summary="لیست امتیاز ها",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="score",in="query",required=false, @OA\Schema(type="integer"),description="score"),
     *     @OA\Parameter(name="gym_id",in="query",required=false, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="ip",in="query",required=false, @OA\Schema(type="string"),description="ip"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ScoreIndexRequest $request): JsonResponse
    {
        $scores = $this->scoreService->index($request);
        return ResponseHelper::responseSuccess(data: $scores);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/scores/{id}",
     *     tags={"scores"},
     *     summary="لیست امتیاز تکی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ScoreShowRequest $request, $score_id): JsonResponse
    {
        $score = $this->scoreService->show($request, $score_id);
        return $score ? ResponseHelper::responseSuccessShow(data: $score) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/scores",
     *     tags={"scores"},
     *     summary="ذخیره امتیاز",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="score",in="query",required=true, @OA\Schema(type="integer"),description="score"),
     *     @OA\Parameter(name="gym_id",in="query",required=true, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="ip",in="query",required=false, @OA\Schema(type="string"),description="ip"),
     *     @OA\Parameter(name="user_agent",in="query",required=false, @OA\Schema(type="string"),description="user_agent"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ScoreStoreRequest $request): JsonResponse
    {
        $score = $this->scoreService->store($request);
        return $score ? ResponseHelper::responseSuccessStore(data: $score) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/scores/{id}",
     *     tags={"scores"},
     *     summary="ویرایش امتیاز",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="score",in="query",required=false, @OA\Schema(type="integer"),description="score"),
     *     @OA\Parameter(name="gym_id",in="query",required=false, @OA\Schema(type="integer"),description="gym_id"),
     *     @OA\Parameter(name="ip",in="query",required=false, @OA\Schema(type="string"),description="ip"),
     *     @OA\Parameter(name="user_agent",in="query",required=false, @OA\Schema(type="string"),description="user_agent"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ScoreUpdateRequest $request, $score_id): JsonResponse
    {
        $score = $this->scoreService->update($request, $score_id);
        return $score ? ResponseHelper::responseSuccessUpdate(data: $score) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/scores/{id}",
     *     tags={"scores"},
     *     summary="حذف امتیاز",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($score_id): JsonResponse
    {
        $status_delete = $this->scoreService->destroy($score_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
