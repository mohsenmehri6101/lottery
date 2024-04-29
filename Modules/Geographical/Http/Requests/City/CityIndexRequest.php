<?php

namespace Modules\Geographical\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Geographical\Entities\City;
use function convert_withs_from_string_to_array;

class CityIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', City::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:cities,id',
            'name' => 'nullable|string',
            'is_center' => 'nullable|boolean',
            'province_id' => 'nullable|string',
            'search' => 'nullable|filled',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
