<?php

namespace Modules\Authorization\Http\Requests\Permission;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Authorization\Entities\Permission;

class PermissionShowRequest extends FormRequest
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

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows_permissions",
        ];
    }
}
