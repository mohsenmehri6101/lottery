<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 */
class Attribute extends Model
{
    protected $table = 'gyms_attributes';

    public $timestamps=false;

    protected $fillable = [
        'id',
        'name',
        'slug',
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
        return $this->belongsToMany(Gym::class, 'attribute_gym');
    }
}
