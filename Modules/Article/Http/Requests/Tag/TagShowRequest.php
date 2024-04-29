<?php

namespace Modules\Article\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Tag;
use function convert_withs_from_string_to_array;

class TagShowRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Tag::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
