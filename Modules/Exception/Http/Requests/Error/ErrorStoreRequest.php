<?php

namespace Modules\Exception\Http\Requests\Error;

use Illuminate\Foundation\Http\FormRequest;

class ErrorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
        // todo return user_have_permission(PermissionEnum::role_index);
    }

    public function rules(): array
    {
        return [
            'name'=>'nullable|filled|string',
            'persian_name'=>'nullable|filled|string',
        ];
    }

    public function attributes(): array
    {
        return [];
    }
}
