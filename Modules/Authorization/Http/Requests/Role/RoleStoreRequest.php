<?php

namespace Modules\Authorization\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|filled|string|unique:roles,name',
            'persian_name' => 'required|filled|string',
            'tag' => 'nullable|string|filled',
            'parent' => 'nullable|numeric|filled|exists:roles,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('custom.authorization.roles.fields.name'),
            'persian_name' => trans('custom.authorization.roles.fields.persian_name'),
            'tag' => trans('custom.authorization.roles.fields.tag'),
            'parent' => trans('custom.authorization.roles.fields.parent'),
        ];
    }
}
