<?php

namespace Modules\Payment\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BankUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'nullable|filled|string',
            'persian_name' => 'nullable|filled|string',
        ];
    }
}
