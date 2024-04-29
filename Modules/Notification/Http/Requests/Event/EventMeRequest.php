<?php

namespace Modules\Notification\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Notification\Entities\Event;

class EventMeRequest extends FormRequest
{
    protected function prepareForValidation()
    {
    }

    public function rules(): array
    {
        $min_priority_limit = config('configs.notifications.notification.min_priority_limit');
        $max_priority_limit = config('configs.notifications.notification.max_priority_limit');
        $relations_permissible = implode(',', Event::$relations_ ?? []);

        return [
            'id' => 'nullable|exists:events,id',
            'name' => 'nullable|filled',
            'title' => 'nullable|filled',
            'tag' => 'nullable|filled',
            'description' => 'nullable|filled',
            'priority' => "nullable|min:$min_priority_limit|mx:$max_priority_limit",
            'notification_template_id' => 'nullable|exists:notification_template,id',

//            'withs' => 'nullable|array',
//            'withs.*' => "nullable|string|in:$relations_permissible",

            'selects' => 'nullable|array',
            'selects_pivot' => 'nullable|array',

            'pivot' => 'nullable|array',
            'pivot.user_id' => 'nullable|exists:users,id',
            'pivot.event_id' => 'nullable|exists:events,id',
            'pivot.channel_id' => 'nullable|exists:channels,id',
            'pivot.status' => 'nullable|boolean',

        ];
    }

}
