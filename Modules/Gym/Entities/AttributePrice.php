<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $gym_id
 * @property integer $price
 */
class AttributePrice extends Model
{
    protected $table = 'attribute_gym_prices';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'attribute_id',
        'gym_id',
        'price',
    ];

    protected $casts = [
        'id' => 'integer',
        'attribute_id' => 'integer',
        'gym_id' => 'integer',
        'price' => 'decimal:3',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'attribute',
        'gym',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function reserves(): BelongsToMany
    {
        return $this->belongsToMany(Reserve::class, 'attribute_gym_price_reserve');
    }
}
