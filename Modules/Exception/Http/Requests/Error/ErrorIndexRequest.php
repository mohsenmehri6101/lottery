<?php

namespace Modules\Exception\Http\Requests\Error;

use Illuminate\Foundation\Http\FormRequest;

class ErrorIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
        // todo return user_have_permission(PermissionEnum::role_index);
    }

    public function rules(): array
    {
        return [
            'paginate' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1',

            'id' => 'nullable|exists:errors,id',
            'url' => 'nullable',
            'status_code' => 'nullable|numeric',
            'exception' => 'nullable|string',
            'message' => 'nullable|string',
            'user_creator' => 'nullable|exists:users,id',

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => trans('custom.exceptions.errors.fields.id'),
            'url' => trans('custom.exceptions.errors.fields.url'),
            'status_code' => trans('custom.exceptions.errors.fields.status_code'),
            'exception' => trans('custom.exceptions.errors.fields.exception'),
            'message' => trans('custom.exceptions.errors.fields.message'),
            'user_creator' => trans('custom.exceptions.errors.fields.user_creator'),
            'stack_trace' => trans('custom.exceptions.errors.fields.stack_trace'),
            'requests' => trans('custom.exceptions.errors.fields.requests'),
            'headers' => trans('custom.exceptions.errors.fields.headers'),
            'user_agent' => trans('custom.exceptions.errors.fields.user_agent'),
            'extra_date' => trans('custom.exceptions.errors.fields.extra_date'),
            'created_at' => trans('custom.exceptions.errors.fields.created_at'),
            'updated_at' => trans('custom.exceptions.errors.fields.updated_at'),
            'deleted_at' => trans('custom.exceptions.errors.fields.deleted_at'),
        ];
    }

}
