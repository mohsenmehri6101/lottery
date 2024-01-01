<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property integer $notification_id
 * @property integer $user_id
 * @property boolean $use_in_db
 * @property $read_at
 * @property $created_at
 */
class NotificationUser extends Model
{
    protected $table = 'notification_user';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'notification_id',
        'user_id',
        'use_in_db',
        'read_at',
        'created_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'notification_id' => 'integer',
        'user_id' => 'integer',
        'use_in_db' => 'boolean',
        'read_at' => 'timestamp',
        'created_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'notification',
        'user',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            # created_at
            if (is_null($item?->created_at)) {
                $item->created_at = now()->timestamp;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    public function freshTimestamp(): float|Carbon|int|string
    {
        return now()->timestamp;
        # return Date::now();
    }

    public function read(): bool
    {
        return $this->read_at !== null;
    }

    public function unread(): bool
    {
        return $this->read_at === null;
    }

}
