<?php

namespace Modules\Payment\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Payment\Http\Requests\Bank\PaymentIndexRequest;
use Modules\Payment\Http\Requests\Bank\PaymentShowRequest;
use Modules\Payment\Http\Requests\Bank\BankStoreRequest;
use Modules\Payment\Http\Requests\Bank\BankUpdateRequest;
use Modules\Payment\Services\BankService;

class BankController extends Controller
{
    public function __construct(public BankService $bankService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/banks",
     *     tags={"banks"},
     *     summary="list banks",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="persian_name"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(PaymentIndexRequest $request): JsonResponse
    {
        $banks = $this->bankService->index($request);
        return ResponseHelper::responseSuccess(data: $banks);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/banks/{id}",
     *     tags={"banks"},
     *     summary="show bank",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(PaymentShowRequest $request, $bank_id): JsonResponse
    {
        $bank = $this->bankService->show($request, $bank_id);
        return $bank ? ResponseHelper::responseSuccessShow(data: $bank) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/banks",
     *     tags={"banks"},
     *     summary="store bank",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="persian_name"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(BankStoreRequest $request): JsonResponse
    {
        $bank = $this->bankService->store($request);
        return ResponseHelper::responseSuccessStore(data: $bank);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/banks/{id}",
     *     tags={"banks"},
     *     summary="update bank",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="persian_name"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(BankUpdateRequest $request, $bank_id): JsonResponse
    {
        $bank = $this->bankService->update($request, $bank_id);
        return ResponseHelper::responseSuccessUpdate(data: $bank);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/banks/{id}",
     *     tags={"banks"},
     *     summary="delete bank",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($bank_id): JsonResponse
    {
        $this->bankService->destroy($bank_id);
        return ResponseHelper::responseSuccessDelete();
    }

}
