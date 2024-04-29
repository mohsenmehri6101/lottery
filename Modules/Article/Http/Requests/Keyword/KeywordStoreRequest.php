<?php

namespace Modules\Article\Http\Requests\Keyword;

use Illuminate\Foundation\Http\FormRequest;

class KeywordStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keyword' => 'required|filled|unique:keywords,keyword',
        ];
    }
}
