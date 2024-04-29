<?php

namespace Modules\Payment\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BankIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'id' => 'nullable|exists:banks,id',

            'name' => 'nullable',
            'persian_name' => 'nullable',

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }
}
