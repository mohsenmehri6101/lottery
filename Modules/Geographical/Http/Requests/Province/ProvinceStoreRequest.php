<?php

namespace Modules\Geographical\Http\Requests\Province;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|filled',
            'status' => 'nullable|numeric',
        ];
    }

}
