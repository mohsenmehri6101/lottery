<?php

namespace Modules\Payment\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Transaction;

class TransactionStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $specifications = implode(',', Transaction::getStatusSpecifications());
        $transaction_types = implode(',', Transaction::getStatusTransactionTypes());
        $operations = implode(',', Transaction::getStatusOperationTypes());

        return [
            'user_destination' => 'required|integer|exists:users,id',
            'user_resource' => 'required|integer|exists:users,id',

            'price'=>'',
            'description'=>'',
            'timed_at'=>'',
            'specification' => "nullable|in:$specifications",
            'transaction_type' => "nullable|in:$transaction_types",
            'operation_type' => "nullable|in:$operations",
        ];
    }

}
