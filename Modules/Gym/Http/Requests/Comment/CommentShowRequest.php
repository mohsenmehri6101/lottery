<?php

namespace Modules\Gym\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Comment;
use function convert_withs_from_string_to_array;

class CommentShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        # gym parent allChildren children
        $withs_allows = implode(',', Comment::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
