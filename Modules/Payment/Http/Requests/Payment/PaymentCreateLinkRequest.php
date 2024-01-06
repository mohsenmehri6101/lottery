<?php

namespace Modules\Payment\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentCreateLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'factor_id' => 'nullable|exists:factors,id',
        ];
    }
}
