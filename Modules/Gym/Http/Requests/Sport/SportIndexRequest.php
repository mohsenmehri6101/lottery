<?php

namespace Modules\Gym\Http\Requests\Sport;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Sport;
use function convert_withs_from_string_to_array;

class SportIndexRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Sport::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:sports,id',
            'name' => 'nullable',
            'slug' => 'nullable',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }

}
