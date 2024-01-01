<?php

namespace Modules\Config\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Config\Entities\Config;

class ConfigShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $relations_permissible = implode(',', Config::$relations_ ?? []);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations_permissible",
        ];
    }

}
