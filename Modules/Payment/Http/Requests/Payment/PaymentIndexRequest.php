<?php

namespace Modules\Payment\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Payment;

class PaymentIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $statuses_payments = implode(',', Payment::getStatusPayment());
        $withs_allows = implode(',', Payment::$relations_);

        // todo check payment show just user_id him self not all of the payment list.

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'id' => 'nullable|exists:payments,id',

            'status' => "nullable|numeric|in:$statuses_payments",
            'resnumber' => 'nullable',
            'price' => 'nullable',

            'factor_id' => 'nullable|exists:factors,id',
            'user_id' => 'nullable|exists:users,id',
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }

}
