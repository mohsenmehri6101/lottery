<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property integer $score
 * @property integer $gym_id
 * @property integer $user_id
 */
class Score extends Model
{
    protected $table = 'scores';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'score',
        'gym_id',
        'user_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'score' => 'integer',
        'gym_id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'gym',
        'user',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            # user_id
            if (is_null($item?->user_id)) {
                $item->user_id = get_user_id_login();
            }

        });
        static::updating(function ($item) {
            # user_id
            if (is_null($item?->user_id)) {
                $item->user_id = get_user_id_login();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
