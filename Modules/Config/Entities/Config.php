<?php

namespace Modules\Config\Entities;

use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $id
 * @property $key
 * @property $title
 * @property $value
 * @property $tag
 * @property $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Config extends Model
{
    use UserEditor, SoftDeletes;

    protected $table = 'configs';

    protected $fillable = [
        'id',
        'key',
        'title',
        'value',
        'tag',
        'user_editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'key' => 'string',
        'title' => 'string',
        'tag' => 'string',
        'value' => 'json',
        'user_editor' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'userEditor',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
        static::updating(function ($item) {
            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
    }

}
