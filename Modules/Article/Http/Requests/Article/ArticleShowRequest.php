<?php

namespace Modules\Article\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Article;
use function convert_withs_from_string_to_array;

class ArticleShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }
    public function rules(): array
    {
        $withs_allows = implode(',', Article::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }
}
