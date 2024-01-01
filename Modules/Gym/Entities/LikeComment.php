<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $comment_id
 * @property integer $type
 */
class LikeComment extends Model
{
    protected $table = 'likes_comment';

    public $timestamps = false;

    const type_dislike = 0;
    const type_like = 1;

    protected $fillable = [
        'id',
        'user_id',
        'comment_id',
        'type',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'comment_id' => 'integer',
        'type' => 'integer',
    ];

    public static array $relations_ = [
        'user',
        'comment',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            # user_id
            if (is_null($item?->user_id)) {
                $item->user_id = set_user_creator();
            }
        });
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
