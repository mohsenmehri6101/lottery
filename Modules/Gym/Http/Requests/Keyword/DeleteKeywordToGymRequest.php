<?php

namespace Modules\Gym\Http\Requests\Keyword;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Services\KeywordService;

class DeleteKeywordToGymRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if ($this->has('keywords') && $this->filled('keywords')) {
            $keywords = $this->get('keywords', []);
            $keyword = $this->get('keyword_id');
            $keyword = KeywordService::convertKeywordToId($keyword);
            $keywords = KeywordService::prepare_keywords($keywords, $keyword)?->toArray() ?? [];
            if (!empty($keywords)) {
                $this->merge(['keywords' => $keywords ?? null, 'keyword_id' => null]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'gym_id' => 'required|exists:gyms,id',
            'touch' => 'nullable|boolean',
            'keyword_id' => 'required_without:keywords|filled|exists:keywords,id',
            'keywords' => 'required_without:keyword_id|array',
            'keywords.*' => 'required|filled|exists:keywords,id',
        ];
    }
}
