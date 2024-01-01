<?php

namespace Modules\Faq\Http\Requests\Faq;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Faq\Entities\Faq;
use function convert_withs_from_string_to_array;

class FaqShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Faq::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }
}
