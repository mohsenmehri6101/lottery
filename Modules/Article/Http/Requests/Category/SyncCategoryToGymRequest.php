<?php

namespace Modules\Article\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Services\CategoryService;

class SyncCategoryToArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:articles,id',
            'detach' => 'nullable|boolean',
            'category_id' => 'required_without:categories|filled|exists:categories,id',
            'categories' => 'required_without:category_id|array',
            'categories.*' => 'required|filled|exists:categories,id',
        ];
    }

}
