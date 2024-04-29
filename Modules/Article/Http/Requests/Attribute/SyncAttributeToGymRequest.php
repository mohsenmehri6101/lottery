<?php

namespace Modules\Article\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class SyncAttributeToArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:articles,id',
            'detach' => 'nullable|boolean',
            'attribute_id' => 'required_without:attributes|filled|exists:articles_attributes,id',
            'attributes' => 'required_without:attribute_id|array',
            'attributes.*' => 'required|filled|exists:articles_attributes,id',
        ];
    }
}
