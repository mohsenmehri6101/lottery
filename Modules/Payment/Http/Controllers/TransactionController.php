<?php

namespace Modules\Payment\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Payment\Http\Requests\Transaction\TransactionIndexRequest;
use Modules\Payment\Http\Requests\Transaction\TransactionShowRequest;
use Modules\Payment\Http\Requests\Transaction\MyTransactionRequest;
use Modules\Payment\Services\TransactionService;

class TransactionController extends Controller
{
    public function __construct(public TransactionService $transactionService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transactions",
     *     tags={"transactions"},
     *     summary="List transactions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate", in="query", required=false, @OA\Schema(type="string"), description="paginate"),
     *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="string"), description="per_page"),
     *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="string"), description="page"),
     *     @OA\Parameter(name="id", in="query", required=false, @OA\Schema(type="integer"), description="id"),
     *     @OA\Parameter(name="user_destination", in="query", required=false, @OA\Schema(type="integer"), description="user_destination"),
     *     @OA\Parameter(name="user_resource", in="query", required=false, @OA\Schema(type="integer"), description="user_resource"),
     *     @OA\Parameter(name="price", in="query", required=false, @OA\Schema(type="string"), description="price"),
     *     @OA\Parameter(name="description", in="query", required=false, @OA\Schema(type="string"), description="description"),
     *     @OA\Parameter(name="specification", in="query", required=false, @OA\Schema(type="integer"), description="specification"),
     *     @OA\Parameter(name="transaction_type", in="query", required=false, @OA\Schema(type="integer"), description="transaction_type"),
     *     @OA\Parameter(name="operation_type", in="query", required=false, @OA\Schema(type="integer"), description="operation_type"),
     *     @OA\Parameter(name="user_creator", in="query", required=false, @OA\Schema(type="integer"), description="user_creator"),
     *     @OA\Parameter(name="user_editor", in="query", required=false, @OA\Schema(type="integer"), description="user_editor"),
     *     @OA\Parameter(name="timed_at", in="query", required=false, @OA\Schema(type="string"), description="timed_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:userDestination,userResource,userCreator,userEditor"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function index(TransactionIndexRequest $request): JsonResponse
    {
        $transactions = $this->transactionService->index($request);
        return ResponseHelper::responseSuccess(data: $transactions);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transactions/my-transactions",
     *     tags={"transactions"},
     *     summary="List user's transactions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function myTransaction(MyTransactionRequest $request): JsonResponse
    {
        $transactions = $this->transactionService->myTransaction($request);
        return ResponseHelper::responseSuccess(data: $transactions);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transactions/{id}",
     *     tags={"transactions"},
     *     summary="Show transaction details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer"), description="Transaction ID"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:userDestination,userResource,userCreator,userEditor"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Transaction not found", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function show(TransactionShowRequest $request, $transaction_id): JsonResponse
    {
        $transaction = $this->transactionService->show($request, $transaction_id);
        return $transaction ? ResponseHelper::responseSuccessShow(data: $transaction) : ResponseHelper::responseFailedShow();
    }

}
