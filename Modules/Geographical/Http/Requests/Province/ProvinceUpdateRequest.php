<?php

namespace Modules\Geographical\Http\Requests\Province;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|filled',
            'status' => 'nullable|numeric',
        ];
    }
}
