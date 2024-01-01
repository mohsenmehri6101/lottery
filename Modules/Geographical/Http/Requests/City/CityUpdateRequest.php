<?php

namespace Modules\Geographical\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class CityUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|filled',
            'is_center' => 'nullable|boolean',
            'province_id' => 'nullable|exists:provinces,id',
            'status' => 'nullable|numeric',
        ];
    }

}
