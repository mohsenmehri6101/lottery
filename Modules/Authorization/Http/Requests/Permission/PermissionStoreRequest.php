<?php

namespace Modules\Authorization\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PermissionStoreRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $name = $this->get('name') ?? null;
        $persian_name = $this->get('persian_name') ?? null;

        /*if ((is_null($name) || !filled($name)) && (!is_null($persian_name) && filled($persian_name))) {
            $name = Str::slug($persian_name);
            $this->merge(['name' => $name ?? null]);
        }*/
    }

    public function rules(): array
    {
        return [
            'name' => 'required|filled|string|unique:permissions,name',
            'persian_name' => 'nullable|filled|string',
            'parent' => 'nullable|numeric|filled|exists:permissions,id',
            'tag' => 'nullable|string|filled',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => trans('custom.authorization.permissions.fields.name'),
            'persian_name' => trans('custom.authorization.permissions.fields.persian_name'),
            'parent' => trans('custom.authorization.permissions.fields.parent'),
            'tag' => trans('custom.authorization.permissions.fields.tag'),
        ];
    }

}
