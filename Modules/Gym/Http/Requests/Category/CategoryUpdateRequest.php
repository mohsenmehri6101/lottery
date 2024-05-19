<?php

namespace Modules\Gym\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|filled',
            'parent' => 'nullable|exists:categories,id',
        ];
    }

}
