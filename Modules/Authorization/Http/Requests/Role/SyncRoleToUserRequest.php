<?php

namespace Modules\Authorization\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Authorization\Services\RoleService;

class SyncRoleToUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|filled|exists:users,id',
            'detach' => 'nullable|boolean',
            'role_id' => 'required_without:roles|filled|exists:roles,id',
            'roles' => 'required_without:role_id|array',
            'roles.*' => 'required|filled|exists:roles,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => trans('custom.authorization.roles.fields.user_id'),
            'detach' => trans('custom.authorization.roles.fields.detach'),
            'role_id' => trans('custom.authorization.roles.fields.id'),
            'roles' => trans('custom.authorization.roles.fields.roles'),
        ];
    }

}
