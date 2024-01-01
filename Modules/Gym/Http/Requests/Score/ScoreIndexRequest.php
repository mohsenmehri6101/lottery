<?php

namespace Modules\Gym\Http\Requests\Score;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Score;
use function convert_withs_from_string_to_array;

class ScoreIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Score::$relations_);
        $min_score_limit= config('configs.gyms.scores.min_score_limit');
        $max_score_limit= config('configs.gyms.scores.max_score_limit');

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:scores,id',
            'score' => "nullable|min:$min_score_limit|max:$max_score_limit",
            'gym_id' => 'nullable|exists:gyms,id',
            'user_id' => 'nullable|exists:users,id',
            'ip' => 'nullable',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
        ];
    }
}
