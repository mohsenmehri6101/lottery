<?php

namespace Modules\Article\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAttributeToArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:articles,id',
            'touch' => 'nullable|boolean',
            'attribute_id' => 'required_without:attributes|filled|exists:articles_attributes,id',
            'attributes' => 'required_without:attribute_id|array',
            'attributes.*' => 'required|filled|exists:articles_attributes,id',
        ];
    }
}
