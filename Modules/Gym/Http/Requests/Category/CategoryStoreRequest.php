<?php

namespace Modules\Gym\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|filled|unique:categories,name',
            'parent' => 'nullable|exists:categories,id',
        ];
    }

    public function attributes()
    {
        // fix from name and parent persian
        return [
            'name'=>'نام',
        ];
    }
}
