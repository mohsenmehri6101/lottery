<?php

namespace Modules\Gym\Http\Requests\Gym;

use Illuminate\Foundation\Http\FormRequest;

class DeleteImageGymRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['images' => convert_withs_from_string_to_array(withs: $this->get(key: 'images'))]);
    }
    public function rules(): array
    {
        return [
            # images
            'image_id' => 'nullable|filled|exists:images_gyms,id',
            'images' => 'nullable|array',
            'images.*' => 'required|filled|exists:images_gyms,id',
        ];
    }
}
