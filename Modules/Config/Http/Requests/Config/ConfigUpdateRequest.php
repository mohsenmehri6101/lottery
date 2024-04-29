<?php

namespace Modules\Config\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class ConfigUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => 'required|string|filled',
            'title' => 'nullable',
            'value' => 'nullable',
            'tag' => 'nullable',
        ];
    }
}
