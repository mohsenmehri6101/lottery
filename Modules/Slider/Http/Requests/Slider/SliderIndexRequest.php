<?php

namespace Modules\Slider\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Slider\Entities\Slider;

class SliderIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $statuses_sliders = implode(',', Slider::getStatusSlider());
        $relations = implode(',', Slider::$relations_ ?? []);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'id' => 'nullable|numeric|exists:sliders,id',
            'title' => 'nullable|string',
            'link' => 'nullable|string',
            'text' => 'nullable|string',
            'status' => "nullable|in:$statuses_sliders",
            'city_id' => 'nullable|numeric|exists:cities,id',

            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }
}
