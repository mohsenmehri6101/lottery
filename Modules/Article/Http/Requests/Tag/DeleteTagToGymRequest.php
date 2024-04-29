<?php

namespace Modules\Article\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Services\TagService;

class DeleteTagToArticleRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if ($this->has('tags') && $this->filled('tags')) {
            $tags = $this->get('tags', []);
            $category = $this->get('tag_id');
            $category = TagService::convertTagToId($category);
            $tags = TagService::prepare_tags($tags, $category)?->toArray() ?? [];
            if (!empty($tags)) {
                $this->merge(['tags' => $tags ?? null, 'tag_id' => null]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:articles,id',
            'touch' => 'nullable|boolean',
            'tag_id' => 'required_without:tags|filled|exists:tags,id',
            'tags' => 'required_without:tag_id|array',
            'tags.*' => 'required|filled|exists:tags,id',
        ];
    }

}
