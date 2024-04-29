<?php

namespace Modules\Payment\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Payment\Http\Requests\Factor\FactorIndexRequest;
use Modules\Payment\Http\Requests\Factor\FactorShowRequest;
use Modules\Payment\Http\Requests\Factor\FactorStoreRequest;
use Modules\Payment\Http\Requests\Factor\FactorUpdateRequest;
use Modules\Payment\Http\Requests\Factor\MyFactorRequest;
use Modules\Payment\Http\Requests\Factor\MyGymsFactorRequest;
use Modules\Payment\Services\FactorService;

class FactorController extends Controller
{
    public function __construct(public FactorService $factorService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/factors",
     *     tags={"factors"},
     *     summary="list factors",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="factor id"),
     *     @OA\Parameter(name="reserve_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_ids[]", in="query", required=false, @OA\Schema(type="array", @OA\Items(type="integer")), description="Array of reserve_ids"),
     *     @OA\Parameter(name="code",in="query",required=false, @OA\Schema(type="string"),description="code"),
     *     @OA\Parameter(name="total_price",in="query",required=false, @OA\Schema(type="string"),description="total_price"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="user_id"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="string"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="string"),description="user_editor"),
     *     @OA\Parameter(name="payment_id",in="query",required=false, @OA\Schema(type="string"),description="payment_id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:gym,userCreator,userEditor,payments,paymentPaid,user,reserves"),
     *     @OA\Parameter(name="order_by",in="query",required=false,@OA\Schema(type="string"),description="Column to sort by"),
     *     @OA\Parameter(name="direction_by",in="query",required=false,@OA\Schema(type="string", enum={"asc", "desc"}),description="Sort direction (asc or desc)"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(FactorIndexRequest $request): JsonResponse
    {
        $factors = $this->factorService->index($request);
        return ResponseHelper::responseSuccess($factors);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/factors/my-factor",
     *     tags={"factors"},
     *     summary="list factors",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="factor id"),
     *     @OA\Parameter(name="reserve_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_ids[]", in="query", required=false, @OA\Schema(type="array", @OA\Items(type="integer")), description="Array of reserve_ids"),
     *     @OA\Parameter(name="code",in="query",required=false, @OA\Schema(type="string"),description="code"),
     *     @OA\Parameter(name="total_price",in="query",required=false, @OA\Schema(type="string"),description="total_price"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="string"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="string"),description="user_editor"),
     *     @OA\Parameter(name="payment_id",in="query",required=false, @OA\Schema(type="string"),description="payment_id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:gym,userCreator,userEditor,payments,paymentPaid,user,reserves"),     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myFactor(MyFactorRequest $request): JsonResponse
    {
        $factors = $this->factorService->myFactor($request);
        return ResponseHelper::responseSuccess($factors);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/factors/my-gyms-factor",
     *     tags={"factors"},
     *     summary="لیست فاکتورهای سالن های ورزشی من",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="factor id"),
     *     @OA\Parameter(name="reserve_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_ids[]", in="query", required=false, @OA\Schema(type="array", @OA\Items(type="integer")), description="Array of reserve_ids"),
     *     @OA\Parameter(name="code",in="query",required=false, @OA\Schema(type="string"),description="code"),
     *     @OA\Parameter(name="total_price",in="query",required=false, @OA\Schema(type="string"),description="total_price"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="string"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="string"),description="user_editor"),
     *     @OA\Parameter(name="payment_id",in="query",required=false, @OA\Schema(type="string"),description="payment_id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myGymsFactor(MyGymsFactorRequest $request): JsonResponse
    {
        $factors = $this->factorService->myGymsFactor($request);
        return ResponseHelper::responseSuccess($factors);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/factors/{id}",
     *     tags={"factors"},
     *     summary="show factor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="factor id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(FactorShowRequest $request, $factor_id): JsonResponse
    {
        $factor = $this->factorService->show($request, $factor_id);
        return $factor ? ResponseHelper::responseSuccessShow($factor) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/factors",
     *     tags={"factors"},
     *     summary="store factor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="reserve_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_ids", in="query", required=false, @OA\Schema(type="array", @OA\Items(type="integer")), description="Array of reserve IDs"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="user_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(FactorStoreRequest $request): JsonResponse
    {
        $factor = $this->factorService->store($request);
        return $factor ? ResponseHelper::responseSuccessStore($factor) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/factors/{id}",
     *     tags={"factors"},
     *     summary="update factor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="factor id"),
     *     @OA\Parameter(name="reserve_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_id"),
     *     @OA\Parameter(name="reserve_ids", in="query", required=false, @OA\Schema(type="array", @OA\Items(type="integer")), description="Array of reserve IDs"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="user_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(FactorUpdateRequest $request, $factor_id): JsonResponse
    {
        $factor = $this->factorService->update($request, $factor_id);
        return $factor ? ResponseHelper::responseSuccessUpdate($factor) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/factors/{id}",
     *     tags={"factors"},
     *     summary="delete factor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="factor id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($factor_id): JsonResponse
    {
        return $this->factorService->destroy($factor_id) ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/factors/statuses",
     *     tags={"factors"},
     *     summary="status available factor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function listStatusFactor($status = null): JsonResponse
    {
        $status_factors = $this->factorService->listStatusFactor($status);
        $status_factors = collect($status_factors)->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        });
        return ResponseHelper::responseSuccess($status_factors);
    }
}
