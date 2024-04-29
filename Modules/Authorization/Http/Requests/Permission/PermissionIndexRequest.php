<?php

namespace Modules\Authorization\Http\Requests\Permission;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Authorization\Entities\Permission;

class PermissionIndexRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_likes();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    protected function prepareForValidation()
    {
                $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows_permissions = implode(',', Permission::$relations_);
        # roles,parentModel,children
        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'selects' => 'nullable|array',
            'id' => 'nullable|filled|numeric|exists:permissions,id',
            'name' => 'nullable|filled|string|likes:permissions,name',
            'role_id' => 'required|integer|exists:roles,id',
            'tag' => 'nullable|filled|string',
            'parent' => 'nullable|filled|string',
            'persian_name' => 'nullable|filled|likes:permissions,persian_name',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows_permissions",
        ];
    }

    public function attributes(): array
    {
        return [
            'paginate' => trans('validation.attributes.paginate'),
            'per_page' => trans('validation.attributes.per_page'),
            'selects' => trans('validation.attributes.selects'),
            'id' => trans('custom.authorization.permissions.fields.id'),
            'name' => trans('custom.authorization.permissions.fields.name'),
            'tag' => trans('custom.authorization.permissions.fields.tag'),
            'parent' => trans('custom.authorization.permissions.fields.parent'),
            'persian_name' => trans('custom.authorization.permissions.fields.persian_name'),
        ];
    }

}
