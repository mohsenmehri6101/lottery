<?php

namespace Modules\Gym\Http\Requests\Sport;

use Illuminate\Foundation\Http\FormRequest;

class SportStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|filled|unique:sports,name',
        ];
    }
}
