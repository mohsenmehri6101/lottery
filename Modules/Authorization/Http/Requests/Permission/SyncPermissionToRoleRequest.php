<?php

namespace Modules\Authorization\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermissionToRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_id' => 'required|filled|exists:roles,id',
            'permission_id' => 'required_without:permissions|filled|exists:permissions,id',
            'permissions' => 'required_without:permission_id|array',
            'permissions.*' => 'required|filled|exists:permissions,id',
            'detach' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'role_id' => trans('custom.authorization.roles.fields.id'),
            'permission_id' => trans('custom.authorization.permissions.fields.id'),
            'permissions' => trans('custom.authorization.permissions.fields.permissions_list'),
            'permissions.*' => trans('custom.authorization.permissions.fields.id'),
            'detach' => trans('custom.defaults.fields.detach'),
        ];
    }
}
