<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Sport extends Model
{
    use SoftDeletes;

    protected $table = 'sports';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'gyms',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            # slug
            if (filled($item?->name ?? null)) {
                $item->slug = get_slug_string($item->name);
            }
        });
        static::updating(function ($item) {
            # slug
            if (filled($item?->name ?? null)) {
                $item->slug = get_slug_string($item->name);
            }
        });
    }

    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(Gym::class, 'sport_gym');
    }
}
