<?php

namespace Modules\Notification\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class NotificationStoreRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['permission_ids' => convert_string_to_array(value: $this->get(key: 'permission_ids'))]);
        $this->merge(['role_ids' => convert_string_to_array(value: $this->get(key: 'role_ids'))]);
        $this->merge(['user_ids' => convert_string_to_array(value: $this->get(key: 'user_ids'))]);
    }

    public function rules(): array
    {
        $min_priority_limit = config('configs.notifications.notification.min_priority_limit');
        $max_priority_limit = config('configs.notifications.notification.max_priority_limit');
        return [
            'title' => 'required_without:text|filled',
            'text' => 'required_without:title|filled',
            'send_at' => 'nullable',
            'priority' => "nullable|min:$min_priority_limit|mx:$max_priority_limit",

            'permission_id' => 'nullable|filled|exists:permissions,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'required|filled|exists:permissions,id',

            'role_id' => 'nullable|filled|exists:roles,id',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'required|filled|exists:roles,id',

            'user_id' => 'nullable|filled|exists:users,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'required|filled|exists:users,id',
        ];
    }
}
