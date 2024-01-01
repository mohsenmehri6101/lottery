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
            'paginate'=>'nullable|boolean',
            'page'=>'nullable|integer|min:1',
            'per_page'=>'nullable|integer|min:1',

            'id' => 'nullable',
            'url' => 'nullable',
            'status_code' => 'nullable',
            'exception' => 'nullable',
            'message' => 'nullable',
            'user_creator' => 'nullable',

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => trans('custom.errors.fields.id'),
            'url' => trans('custom.errors.fields.url'),
            'status_code' => trans('custom.errors.fields.status_code'),
            'exception' => trans('custom.errors.fields.exception'),
            'message' => trans('custom.errors.fields.message'),
            'user_creator' => trans('custom.errors.fields.user_creator'),
            'stack_trace' => trans('custom.errors.fields.stack_trace'),
            'requests' => trans('custom.errors.fields.requests'),
            'headers' => trans('custom.errors.fields.headers'),
            'user_agent' => trans('custom.errors.fields.user_agent'),
            'extra_date' => trans('custom.errors.fields.extra_date'),
            'created_at' => trans('custom.errors.fields.created_at'),
            'updated_at' => trans('custom.errors.fields.updated_at'),
            'deleted_at' => trans('custom.errors.fields.deleted_at'),
        ];
    }
}
