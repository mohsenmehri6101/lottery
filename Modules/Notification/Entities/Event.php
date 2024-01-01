<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $tag
 * @property string $description
 * @property integer $notification_template_id
 * @property integer $priority
 */
class Event extends Model
{
    protected $table = 'events';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'title',
        'tag',
        'description',
        'notification_template_id',
        'priority',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'title' => 'string',
        'tag' => 'string',
        'description' => 'string',
        'notification_template_id' => 'integer',
        'priority' => 'integer',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'users',
        'notificationTemplate',
    ];

    public function notificationTemplate(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'notification_template_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'channel_event_user', 'event_id', 'user_id');
    }
}
