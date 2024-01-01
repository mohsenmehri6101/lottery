<?php

namespace Modules\Authentication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Notification\Entities\Event;
use Modules\Notification\Entities\Notification;

trait NotificationUserTrait
{
    public function notifications(): BelongsToMany
    {
        /** @var Model $this */
        return $this->belongsToMany(Notification::class, 'notification_user', 'user_id', 'notification_id')->latest();
    }

    public function readNotifications(): BelongsToMany
    {
        /** @var Model $this */
        return $this->notifications()->whereNotNull('notification_user.read_at');
    }

    public function unreadNotifications(): BelongsToMany
    {
        /** @var Model $this */
        return $this->notifications()->whereNull('notification_user.read_at');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'channel_event_user', 'user_id', 'event_id');
    }

}
