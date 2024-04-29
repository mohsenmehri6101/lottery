<?php

namespace Modules\Article\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ArticleLikeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article_id' => 'required|filled|exists:articles,id',
            'type' => 'required|filled|string|in:like,dislike',
        ];
    }
}
