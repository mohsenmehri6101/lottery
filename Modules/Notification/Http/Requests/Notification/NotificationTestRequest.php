<?php

namespace Modules\Notification\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class NotificationTestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|filled',
            'message' => 'required|filled',
            'service' => 'nullable|in:ghasedak,mediana',
        ];
    }
}
