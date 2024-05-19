<?php

namespace Modules\Gym\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Comment;
use function convert_withs_from_string_to_array;

class CommentIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Comment::$relations_);
        $statuses_comment = implode(',', Comment::getStatusComment());

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:comments,id',
            'text' => 'nullable|filled',
            'gym_id' => 'nullable|exists:gyms,id',
            'parent' => 'nullable|exists:comments,id',
            'status' => "nullable|numeric|in:$statuses_comment",
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
        ];
    }

}
