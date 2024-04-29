<?php

namespace Modules\Article\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class DeleteImageArticleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['images' => convert_withs_from_string_to_array(withs: $this->get(key: 'images'))]);
    }
    public function rules(): array
    {
        return [
            # images
            'image_id' => 'nullable|filled|exists:images_articles,id',
            'images' => 'nullable|array',
            'images.*' => 'required|filled|exists:images_articles,id',
        ];
    }
}
