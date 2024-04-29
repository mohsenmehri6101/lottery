<?php

namespace Modules\Article\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Attribute;
use function convert_withs_from_string_to_array;

class AttributeIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Attribute::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:articles_attributes,id',
            'name' => 'nullable',
            'slug' => 'nullable',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
