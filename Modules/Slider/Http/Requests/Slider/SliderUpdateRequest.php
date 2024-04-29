<?php

namespace Modules\Slider\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Slider\Entities\Slider;

class SliderUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_sliders = implode(',', Slider::getStatusSlider());

        return [
            'title' => 'nullable|string|filled',
            'image' => "nullable",
            'link' => 'nullable|string',
            'status' => "nullable|in:$statuses_sliders",
            'city_id' => 'nullable|numeric|exists:cities,id',
        ];
    }

}
