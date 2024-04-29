<?php

namespace Modules\Article\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Comment;
use function convert_withs_from_string_to_array;

class CommentShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        # article parent allChildren children
        $withs_allows = implode(',', Comment::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
