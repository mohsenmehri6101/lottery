<?php

namespace Modules\Payment\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Transaction;
use function convert_withs_from_string_to_array;

class TransactionIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array($this->get('withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Transaction::$relations_);
        $specifications = implode(',', Transaction::getStatusSpecifications());
        $transactionTypes = implode(',', Transaction::getStatusTransactionTypes());
        $operations = implode(',', Transaction::getStatusOperationTypes());

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',

            'id' => 'nullable|exists:transactions,id',
            'user_destination' => 'nullable|exists:users,id',
            'user_resource' => 'nullable|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',

            'specification' => "nullable|in:$specifications",
            'transaction_type' => "nullable|in:$transactionTypes",
            'operation_type' => "nullable|in:$operations",

            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',
            'timed_at' => 'nullable|date_format:Y-m-d H:i:s',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
            'updated_at' => 'nullable|date_format:Y-m-d H:i:s',
            'deleted_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

}
