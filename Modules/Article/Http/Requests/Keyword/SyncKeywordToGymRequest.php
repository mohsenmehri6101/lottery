<?php

namespace Modules\Article\Http\Requests\Keyword;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Services\KeywordService;

class SyncKeywordToArticleRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if ($this->has('keywords') && $this->filled('keywords')) {
            $keywords = $this->get('keywords',[]);
            $keyword = $this->get('keyword_id');
            $keyword = KeywordService::convertKeywordToId($keyword);
            $keywords = KeywordService::prepare_keywords($keywords, $keyword)?->toArray() ?? [];
            if (!empty($keywords)) {
                $this->merge(['keywords' => $keywords ?? null,'keyword_id'=>null]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:articles,id',
            'detach' => 'nullable|boolean',
            'keyword_id' => 'required_without:keywords|filled|exists:keywords,id',
            'keywords' => 'required_without:keyword_id|array',
            'keywords.*' => 'required|filled|exists:keywords,id',
        ];
    }
}
