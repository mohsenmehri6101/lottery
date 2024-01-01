<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $gym_id
 * @property string $title
 * @property string $original_name
 * @property string $image
 * @property string $type
 * @property string $url
 */
class Image extends Model
{
    protected $table = 'images_gyms';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'gym_id',
        'title',
        'original_name',
        'image',
        'type',
        'url',
    ];

    protected $casts = [
        'id' => 'integer',
        'gym_id' => 'integer',
        'title' => 'string',
        'original_name' => 'string',
        'image' => 'string',
        'type' => 'string',
        'url' => 'string',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'gym'
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }
}
