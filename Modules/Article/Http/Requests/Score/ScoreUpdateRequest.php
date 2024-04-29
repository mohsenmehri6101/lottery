<?php

namespace Modules\Article\Http\Requests\Score;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ScoreUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function rules(): array
    {
        $min_score_limit = config('configs.articles.scores.min_score_limit');
        $max_score_limit = config('configs.articles.scores.max_score_limit');

        return [
            'score' => "nullable|min:$min_score_limit|max:$max_score_limit",
        ];
    }

}
