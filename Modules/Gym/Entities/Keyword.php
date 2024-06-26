<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property $id
 * @property $keyword
 * @property $slug
 */
class Keyword extends Model
{
    protected $table = 'keywords';

    public $timestamps=false;

    protected $fillable = [
        'id',
        'keyword',
        'slug',
    ];

    protected $casts = [
        'id' => 'integer',
        'keyword' => 'string',
        'slug' => 'string',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'gyms'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            # slug
            if (filled($item?->keyword ?? null)) {
                $item->slug = get_slug_string($item->keyword);
            }

        });
        static::updating(function ($item) {
            # slug
            if (filled($item?->keyword ?? null)) {
                $item->slug = get_slug_string($item->keyword);
            }
        });
    }

    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(Gym::class,'gym_keyword');
    }
}
