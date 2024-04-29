<?php

namespace Modules\Article\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Reserve;

class ReserveBetweenDateRequest extends FormRequest
{
    public function rules(): array
    {
        $fillables = (new Reserve())->getFillable();
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
