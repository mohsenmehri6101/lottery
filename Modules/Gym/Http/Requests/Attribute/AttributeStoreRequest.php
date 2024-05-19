<?php

namespace Modules\Gym\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|filled|unique:gyms_attributes,name',
        ];
    }
}
