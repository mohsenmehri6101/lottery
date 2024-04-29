<?php

namespace Modules\Article\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentLikeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment_id' => 'required|exists:comments,id',
            'type' => 'required|filled|string|in:like,dislike',
        ];
    }

}
