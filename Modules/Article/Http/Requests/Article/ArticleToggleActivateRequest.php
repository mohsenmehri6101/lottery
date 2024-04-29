<?php

namespace Modules\Article\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Article;

class ArticleToggleActivateRequest extends FormRequest
{
    public function rules(): array
    {
        $list_status_allowable = trim(implode(',', [Article::status_active, Article::status_disable]));
        return [
            'status' => "nullable|numeric|in:$list_status_allowable",
        ];
    }
}
