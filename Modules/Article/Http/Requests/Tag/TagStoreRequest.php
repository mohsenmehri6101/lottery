<?php

namespace Modules\Article\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class TagStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tag' => 'required|filled|unique:tags,tag',
            'type' => 'nullable',
        ];
    }

}
