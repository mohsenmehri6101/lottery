<?php

namespace Modules\Config\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class ConfigStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => 'required|string|filled|unique:configs,key',
            'title' => 'nullable',
            'value' => 'nullable',
            'tag' => 'nullable',
        ];
    }

}
