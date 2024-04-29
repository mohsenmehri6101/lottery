<?php

namespace Modules\Article\Http\Requests\Score;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Score;
use function convert_withs_from_string_to_array;

class ScoreShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Score::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
