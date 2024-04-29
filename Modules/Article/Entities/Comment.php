<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $text
 * @property integer $parent
 * @property integer $article_id
 * @property integer $status
 * @property integer $likes_count
 * @property integer $user_id
 * @property $created_at
 * @property $updated_at
 */
class Comment extends Model
{
    const status_unknown = 0;
    const status_reject = 1;
    const status_confirmed = 2;

    protected $table = 'comments';

    protected $fillable = [
        'id',
        'text',
        'parent',
        'article_id',
        'status',
        'likes_count',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'text' => 'string',
        'parent' => 'integer',
        'article_id' => 'integer',
        'status' => 'integer',
        'likes_count' => 'integer',
        'user_id' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'article',
        'parent',
        'allChildren',
        'children',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            # user_id
            if (is_null($item->user_id)) {
                $item->user_id = get_user_id_login();
            }
        });
        static::updating(function ($item) {
            # user_id
            if (is_null($item->user_id)) {
                $item->user_id = get_user_id_login();
            }
        });
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
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

    public static function getStatusCommentTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusCommentPersian();
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

    public static function getStatusComment(): array
    {
        return [
            self::status_unknown,
            self::status_reject,
            self::status_confirmed,
        ];
    }

    public static function getStatusCommentPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_reject => 'رد شده',
            self::status_confirmed => 'تایید شده',
        ];
    }

    public static function like($comment_id, $type = 'like', $user_id = null): int
    {
        $user_id = $user_id ?? get_user_id_login();
        $type = $type === 'like' ? LikeComment::type_like : $type;
        $type = $type === 'dislike' ? LikeComment::type_dislike : $type;

        /** @var Comment $comment */
        $comment = Comment::query()->find($comment_id);
        $fields = ['comment_id' => $comment_id];

        $fields = $user_id ? [...$fields, 'user_id' => $user_id] : $fields;

        /** @var LikeComment $likeComment */
        $likeComment = LikeComment::query()->where($fields)->first();

        if ($likeComment && $likeComment->type == $type) {
            return $comment->likes_count;
        } else {
            $fields_insert = [...$fields, 'type' => $type];
            LikeComment::query()->updateOrCreate($fields,$fields_insert);
            if ($type === LikeComment::type_like) {
                $comment->increment('likes_count');
            } else {
                $comment->decrement('likes_count');
            }
        }
        $comment->save();

        return $comment->likes_count;
    }

    public static function dislike($comment_id, $type = 'dislike', $user_id = null): int
    {
        return self::like(comment_id: $comment_id, type: $type, user_id: $user_id);
    }

}
