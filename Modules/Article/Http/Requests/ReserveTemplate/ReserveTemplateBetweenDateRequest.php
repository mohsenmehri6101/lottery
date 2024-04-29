<?php

namespace Modules\Article\Http\Requests\ReserveTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\ReserveTemplate;

class ReserveTemplateBetweenDateRequest extends FormRequest
{
    public function rules(): array
    {
        $fillables = (new ReserveTemplate())->getFillable();
        $array_fillable = convert_array_to_string($fillables);

        return [
            'from' => 'required',
            'to' => 'required',
            'article_id' => 'required|exists:articles,id',
            'order_by' => "nullable|filled|in:$array_fillable",
            'direction_by' => "nullable|filled|in:asc,desc",
        ];
    }

}
