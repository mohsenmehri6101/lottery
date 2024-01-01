<?php

namespace Modules\Authorization\Entities;

use App\Models\Traits\GetCastsModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Notification\Entities\Notification;
use Spatie\Permission\Models\Permission as PermissionSpatie;

/**
 * @property integer $id
 * @property string $name
 * @property string $persian_name
 * @property string $module
 * @property string $tag
 * @property integer $parent
 * @property $parentModel
 * @property integer $user_creator_id
 * @property string $guard_name
 * @property $created_at
 * @property $updated_at
 * @method function children()
 */
class Permission extends PermissionSpatie
{
    use GetCastsModel;

    protected $table='permissions';

    protected $fillable = [
        'id',
        'name',
        'persian_name',
        'tag',
        'parent',
        'module',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'persian_name' => 'string',
        'tag' => 'string',
        'parent' => 'integer',
        'module' => 'string',
        'guard_name' => 'string',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $guarded = [
        'id' => 'integer',
        'name' => 'string',
        'persian_name' => 'string',
        'tag' => 'string',
        'parent' => 'integer',
        'module' => 'string',
        'guard_name' => 'string',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $hidden = [
        'module',
        'guard_name',
        'parent',
    ];

    public static array $relations_ = [
        'roles',
        'parentModel',
        'children',
        'notifications',
        'allChildren',
        // 'users',
    ];

    public function parentModel(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent', 'id');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('children');
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_permission', 'permission_id','notification_id');
    }

}
