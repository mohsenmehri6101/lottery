<?php

namespace Modules\Payment\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BankStoreRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|filled|string',
            'persian_name' => 'required|filled|string',
        ];
    }
}
