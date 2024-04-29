<?php

namespace Modules\Slider\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Slider\Entities\Slider;

class SliderShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $relations_permissible = implode(',', Slider::$relations_ ?? []);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations_permissible",
        ];
    }

}
