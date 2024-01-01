<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $description
 */
class Channel extends Model
{
    protected $table = 'channels';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'title',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    protected $hidden = [];

    public static array $relations_ = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'channel_event_user', 'channel_id', 'user_id');
    }

}
