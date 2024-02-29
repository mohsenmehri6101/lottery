<?php

namespace Modules\Payment\Services;

use App\Exceptions\Contracts\ForbiddenCustomException;
use Exception;
use Modules\Payment\Http\Requests\Transaction\TransactionIndexRequest;
use Modules\Payment\Http\Requests\Transaction\TransactionShowRequest;
use Modules\Payment\Http\Repositories\TransactionRepository;
use Modules\Payment\Http\Requests\Transaction\MyTransactionRequest;

class TransactionService
{
    public function __construct(public TransactionRepository $transactionRepository)
    {
    }

    public function index(TransactionIndexRequest $request)
    {
        try {
            if(!is_admin()){
                throw new ForbiddenCustomException();
            }

            $fields = $request->validated();
            return $this->transactionRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function myTransaction(MyTransactionRequest $request)
    {
        try {
            $fields = $request->validated();
            $fields['user_destination']=get_user_id_login();
            return $this->transactionRepository->resolve_paginate(inputs: $fields,my_auth: true);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(TransactionShowRequest $request, $transaction_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $withs = $withs ?? [];
            return $this->transactionRepository->withRelations(relations: $withs)->findOrFail($transaction_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

}
