<?php

namespace Modules\Geographical\Http\Requests\Province;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Geographical\Entities\Province;
use function convert_withs_from_string_to_array;
class ProvinceIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Province::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:provinces,id',
            'search' => 'nullable|string',
            'name' => 'nullable|string',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
