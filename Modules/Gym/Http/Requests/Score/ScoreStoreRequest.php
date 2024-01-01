<?php

namespace Modules\Gym\Http\Requests\Score;

use Illuminate\Foundation\Http\FormRequest;

class ScoreStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $min_score_limit = config('configs.gyms.scores.min_score_limit');
        $max_score_limit = config('configs.gyms.scores.max_score_limit');

        return [
            'score' => "nullable|min:$min_score_limit|max:$max_score_limit",
            'gym_id' => 'required|exists:gyms,id',
        ];
    }
}
