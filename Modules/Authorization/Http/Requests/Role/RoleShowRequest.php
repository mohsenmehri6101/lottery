<?php

namespace Modules\Authorization\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Authorization\Entities\Role;

class RoleShowRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Role::$relations_);
        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}
