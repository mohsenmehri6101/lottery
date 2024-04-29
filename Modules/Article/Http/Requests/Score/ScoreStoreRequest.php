<?php

namespace Modules\Article\Http\Requests\Score;

use Illuminate\Foundation\Http\FormRequest;

class ScoreStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $min_score_limit = config('configs.articles.scores.min_score_limit');
        $max_score_limit = config('configs.articles.scores.max_score_limit');

        return [
            'score' => "nullable|min:$min_score_limit|max:$max_score_limit",
            'article_id' => 'required|exists:articles,id',
        ];
    }
}
