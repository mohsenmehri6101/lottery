<?php

namespace Modules\Notification\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authorization\Entities\Permission;
use Modules\Authorization\Entities\Role;

/**
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property $send_at
 * @property integer $priority
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 */
class Notification extends Model
{
    use UserCreator;
    use UserEditor;
    use SoftDeletes;

    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'title',
        'text',
        'send_at',
        'priority',
        'user_creator',
        'user_editor',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'text' => 'string',
        'send_at' => 'timestamp',
        'priority' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'userEditor',
        'userCreator',
        'permissions',
        'roles',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            # user_creator
            if (is_null($item?->user_creator)) {
                $item->user_creator = set_user_creator();
            }

            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = get_user_id_login();
            }

        });
        static::updating(function ($item) {
            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'notification_permission', 'notification_id', 'permission_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'notification_role', 'notification_id', 'role_id');
    }

}
