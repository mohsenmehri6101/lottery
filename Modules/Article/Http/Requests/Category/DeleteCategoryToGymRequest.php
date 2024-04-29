<?php

namespace Modules\Article\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Services\CategoryService;

class DeleteCategoryToArticleRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        if ($this->has('categories') && $this->filled('categories')) {
            /** @var array $categories */
            $categories = $this->get('categories',[]);
            /** @var string|null $category_id */
            $category_id = $this->get('category_id');
            $category_id = CategoryService::convertCategoryToId($category_id);
            $categories = CategoryService::prepare_categories($categories, $category_id)?->toArray() ?? [];
            if (!empty($categories)) {
                $this->merge(['categories' => $categories ?? null,'category_id'=>null]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:articles,id',
            'touch' => 'nullable|boolean',
            'category_id' => 'required_without:categories|filled|exists:categories,id',
            'categories' => 'required_without:category_id|array',
            'categories.*' => 'required|filled|exists:categories,id',
        ];
    }

}
