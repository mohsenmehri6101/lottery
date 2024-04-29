<?php

namespace Modules\Authorization\Entities;

use App\Models\Traits\GetCastsModel;
use App\Permissions\RolesEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Notification\Entities\Notification;
use Spatie\Permission\Models\Role as RoleSpatie;
use Illuminate\Support\Facades\Date;

/**
 * @property integer $id
 * @property string $name
 * @property string $persian_name
 * @property string $tag
 * @property integer $parent
 * @property string $guard_name
 * @property Date $created_at
 * @property Date $updated_at
 * @property Date $deleted_at
 */
class Role extends RoleSpatie
{
    use GetCastsModel;
    protected $table = 'roles';


    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'مدیریت';
    const GYM_MANAGER = 'مسئول سالن ورزشی';
    const USER = 'کاربر معمولی';

    protected $fillable = [
        'id',
        'name',
        'persian_name',
        'tag',
        'parent',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id' => 'integer',
        'name' => 'string',
        'persian_name' => 'string',
        'tag' => 'string',
        'parent' => 'integer',
        'guard_name' => 'string',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public static array $relations_ = [
        'permissions',
        'notifications',
    ];

    public function parentModel(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent', 'id');
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_permission', 'permission_id','notification_id');
    }
}
