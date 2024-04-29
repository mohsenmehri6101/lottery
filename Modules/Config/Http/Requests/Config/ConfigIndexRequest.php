<?php

namespace Modules\Config\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Config\Entities\Config;

class ConfigIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $relations_permissible = implode(',', Config::$relations_ ?? []);

        return [
            'ide' => 'nullable',
            'key' => 'nullable',
            'title' => 'nullable',
            'value' => 'nullable',
            'tag' => 'nullable',
            'user_editor' => 'nullable|numeric|exists:users,id',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations_permissible",
        ];
    }

}
