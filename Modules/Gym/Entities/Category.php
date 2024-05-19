<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $parent
 */
class Category extends Model
{

    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'parent',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'parent' => 'integer',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'gyms',
        'parent',
        'children',
        'allChildren',
    ];

    protected static function boot(): void
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
        return $this->belongsToMany(Gym::class, 'category_gym');
    }

    public function parent(): BelongsTo
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

}
