<?php

namespace Modules\Geographical\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Geographical\Entities\City;
use function convert_withs_from_string_to_array;

class CityShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',',City::$relations_);
        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }
}
