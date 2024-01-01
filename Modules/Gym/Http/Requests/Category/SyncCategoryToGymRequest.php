<?php

namespace Modules\Gym\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Services\CategoryService;

class SyncCategoryToGymRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gym_id' => 'required|exists:gyms,id',
            'detach' => 'nullable|boolean',
            'category_id' => 'required_without:categories|filled|exists:categories,id',
            'categories' => 'required_without:category_id|array',
            'categories.*' => 'required|filled|exists:categories,id',
        ];
    }

}
