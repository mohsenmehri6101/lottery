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

    public function index(TransactionIndexRequest $request): JsonResponse
    {
        $transactions = $this->transactionService->index($request);
        return ResponseHelper::responseSuccess(data: $transactions);
    }

    public function myTransaction(MyTransactionRequest $request): JsonResponse
    {
        $transactions = $this->transactionService->myTransaction($request);
        return ResponseHelper::responseSuccess(data: $transactions);
    }

    public function show(TransactionShowRequest $request, $transaction_id): JsonResponse
    {
        $transaction = $this->transactionService->show($request, $transaction_id);
        return $transaction ? ResponseHelper::responseSuccessShow(data: $transaction) : ResponseHelper::responseFailedShow();
    }
}
