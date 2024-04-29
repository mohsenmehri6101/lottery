<?php

namespace Modules\Payment\Http\Requests\Factor;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Factor;
use function convert_withs_from_string_to_array;

class FactorShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Factor::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
