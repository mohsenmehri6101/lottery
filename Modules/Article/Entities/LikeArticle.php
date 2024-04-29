<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $article_id
 * @property integer $type
 */
class LikeArticle extends Model
{
    protected $table = 'likes_article';

    public $timestamps = false;

    const type_dislike = 0;
    const type_like = 1;

    protected $fillable = [
        'id',
        'user_id',
        'article_id',
        'type',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'article_id' => 'integer',
        'type' => 'integer',
    ];

    public static array $relations_ = [
        'user',
        'article',
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

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function gettypeTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::gettypePersian();
        if (!is_null($status)) {
            if (is_string_persian($status)) {
                return array_search($status, $statuses) ?? null;
            }
            if (is_int($status) && in_array($status, array_keys($statuses))) {
                return $statuses[$status] ?? null;
            }
            return null;
        }
        return $statuses;
    }

    public static function getTypePersian(): array
    {
        return [
            self::type_dislike=> 'dislike',
            self::type_like=> 'like',
        ];
    }

    public static function gettype(): array
    {
        return [
            self::type_dislike,/* 0 */
            self::type_like,/* 1 */
        ];
    }
    // type
}
