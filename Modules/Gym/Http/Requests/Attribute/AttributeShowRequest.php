<?php

namespace Modules\Gym\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Attribute;
use function convert_withs_from_string_to_array;

class AttributeShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Attribute::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
