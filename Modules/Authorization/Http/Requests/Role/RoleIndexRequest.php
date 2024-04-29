<?php

namespace Modules\Authorization\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Authorization\Entities\Role;

class RoleIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows_roles = implode(',', Role::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'id' => 'nullable|filled|numeric|exists:roles,id',
            'name' => 'nullable|filled|string',
            'persian_name' => 'nullable|filled',
            'tag' => 'nullable|filled|string',
            'parent' => 'nullable|numeric|filled|exists:roles,id',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows_roles",
        ];
    }

    public function attributes(): array
    {
        return [
            'paginate' => trans('validation.attributes.paginate'),
            'per_page' => trans('validation.attributes.per_page'),
            'id' => trans('custom.authorization.roles.fields.id'),
            'name' => trans('custom.authorization.roles.fields.name'),
            'persian_name' => trans('custom.authorization.roles.fields.persian_name'),
            'tag' => trans('custom.authorization.roles.fields.tag'),
            'parent' => trans('custom.authorization.roles.fields.parent'),
            'withs' => trans('custom.authorization.roles.fields.withs'),
            'withs.*' => trans('custom.authorization.roles.fields.with'),
        ];
    }

}
