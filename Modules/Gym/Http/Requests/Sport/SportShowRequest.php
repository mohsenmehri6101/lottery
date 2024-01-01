<?php

namespace Modules\Gym\Http\Requests\Sport;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Sport;
use function convert_withs_from_string_to_array;

class SportShowRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Sport::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
