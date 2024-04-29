<?php

namespace Modules\Article\Http\Requests\Keyword;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Keyword;
use function convert_withs_from_string_to_array;

class KeywordIndexRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Keyword::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'id' => 'nullable|exists:keywords,id',
            'keyword' => 'nullable',
            'slug' => 'nullable',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

        ];
    }
}
