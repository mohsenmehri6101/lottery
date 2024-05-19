<?php

namespace Modules\Gym\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Tag;
use function convert_withs_from_string_to_array;

class TagIndexRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Tag::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'id' => 'nullable|exists:tags,id',
            'tag' => 'nullable',
            'slug' => 'nullable',
            'type' => 'nullable',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }

}
