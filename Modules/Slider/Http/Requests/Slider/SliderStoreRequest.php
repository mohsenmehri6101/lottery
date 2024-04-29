<?php

namespace Modules\Slider\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Slider\Entities\Slider;
class SliderStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_sliders = implode(',', Slider::getStatusSlider());

        return [
            'title' => 'nullable|string|filled',
            'image' => 'required',
            'link' => 'nullable|string',
            'status' => "nullable|in:$statuses_sliders",
            'city_id' => 'nullable|numeric|exists:cities,id',
        ];
    }

}
