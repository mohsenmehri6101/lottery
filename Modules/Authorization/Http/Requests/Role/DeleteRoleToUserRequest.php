<?php

namespace Modules\Authorization\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Authorization\Services\RoleService;

class DeleteRoleToUserRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if ($this->has('roles') && $this->filled('roles')) {
            $roles = $this->get('roles') ?? [];
            $role = $this->get('role');
            $role = RoleService::convertRoleToId($role);
            $roles = RoleService::prepare_roles($roles, $role)?->toArray() ?? [];
            if (!empty($roles)) {
                $this->merge(['roles' => $roles ?? null]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|filled',
            'touch' => 'nullable|boolean',
            'role_id' => 'required_without:roles|filled|exists:roles,id',
            'roles' => 'required_without:role_id|array',
            'roles.*' => 'required|filled|exists:roles,id',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => trans('custom.authentication.users.fields.id'),
            'touch'=>trans('custom.authorization.roles.fields.touch'),
            'role_id'=>trans('custom.authorization.roles.fields.id'),
            'roles'=>trans('custom.authorization.roles.fields.roles'),
        ];
    }

}
