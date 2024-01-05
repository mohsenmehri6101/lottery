<?php

namespace Modules\Gym\Http\Requests\ReserveTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;

class ReserveTemplateBetweenDateRequest extends FormRequest
{
    public function rules(): array
    {
        $fillables = (new ReserveTemplate())->getFillable();
        $array_fillable = convert_array_to_string($fillables);

        return [
            'from' => 'required',
            'to' => 'required',
            'gym_id' => 'required|exists:gyms,id',
            'order_by' => "nullable|filled|in:$array_fillable",
            'direction_by' => "nullable|filled|in:asc,desc",
        ];
    }

}
