<?php

namespace Modules\Notification\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $channel_id
 * @property integer $user_creator
 * @property integer $user_editor
 */
class NotificationTemplate extends Model
{
    use UserCreator;
    use UserEditor;

    protected $table = 'notification_template';

    public $timestamps=false;

    protected $fillable = [
        'id',
        'title',
        'text',
        'channel_id',
        'user_creator',
        'user_editor',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'text' => 'string',
        'channel_id' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'userEditor',
        'userCreator',
        'channel'
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

}
