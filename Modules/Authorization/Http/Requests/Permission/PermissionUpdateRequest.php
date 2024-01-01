<?php

namespace Modules\Authorization\Http\Requests\Permission;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class PermissionUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_update_unique();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|filled|string|update_unique:permissions,name',
            'tag' => 'nullable|string|filled',
            'parent' => 'nullable|numeric|filled|exists:permissions,id',
            'persian_name' => 'nullable|filled|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => trans('custom.authorization.permissions.fields.name'),
            'tag' => trans('custom.authorization.permissions.fields.tag'),
            'parent' => trans('custom.authorization.permissions.fields.parent'),
            'persian_name' => trans('custom.authorization.permissions.fields.persian_name'),
        ];
    }

}
