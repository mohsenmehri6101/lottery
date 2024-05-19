<?php

namespace Modules\Gym\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Category;
use function convert_withs_from_string_to_array;

class CategoryIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Category::$relations_);

        $fillables=(new Category())->getFillable();
        $list_fillable=convert_array_to_string($fillables);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'order_by' => "nullable|filled|in:$list_fillable",
            'direction_by' => "nullable|filled|in:asc,desc",
            'id' => 'nullable|exists:categories,id',
            'name' => 'nullable',
            'search' => 'nullable|filled',
            'slug' => 'nullable',
            'parent' => 'nullable|exists:categories,id',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }
}
