<?php

namespace Modules\Gym\Http\Requests\Keyword;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Keyword;
use function convert_withs_from_string_to_array;

class KeywordShowRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Keyword::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
