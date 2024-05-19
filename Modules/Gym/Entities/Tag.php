<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property integer $id
 * @property string $tag
 * @property string $slug
 * @property string $type
 */
class Tag extends Model
{
    protected $table='tags';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'tag',
        'slug',
        'type',
    ];

    protected $casts = [
        'id' => 'integer',
        'tag' => 'string',
        'slug' => 'string',
        'type' => 'string',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'gyms'
    ];

    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(Gym::class,'gym_tag');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            # slug
            if (filled($item?->tag ?? null)) {
                $item->slug = get_slug_string($item->tag);
            }
        });
        static::updating(function ($item) {
            # slug
            if (filled($item?->tag ?? null)) {
                $item->slug = get_slug_string($item->tag);
            }
        });
    }

}
