<?php

namespace Modules\Article\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Reserve;
use function convert_withs_from_string_to_array;

class ReserveShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Reserve::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }
}
