<?php

namespace Modules\Gym\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class SyncAttributeToGymRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gym_id' => 'required|exists:gyms,id',
            'detach' => 'nullable|boolean',
            'attribute_id' => 'required_without:attributes|filled|exists:gyms_attributes,id',
            'attributes' => 'required_without:attribute_id|array',
            'attributes.*' => 'required|filled|exists:gyms_attributes,id',
        ];
    }
}
