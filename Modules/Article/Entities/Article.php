<?php

namespace Modules\Article\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authentication\Entities\User;
use Modules\Geographical\Entities\City;

/**
 * @property integer $id
 * @property integer $name
 * @property string $description
 * @property string $price
 * @property string $latitude
 * @property string $longitude
 * @property integer $city_id
 * @property string $address
 * @property string $short_address
 * @property string $reason_article_disabled
 * @property integer $score
 * @property integer $status
 * @property integer $gender_acceptance
 * @property integer $priority_show
 * @property integer $like_count
 * @property integer $dislike_count
 * @property integer $profit_share_percentage
 * @property integer $user_article_manager_id
 * @property boolean $is_ball
 * @property string $ball_price
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Article extends Model
{
    use SoftDeletes, UserCreator, UserEditor;
    /*  ----------------------------------- */
    const status_unknown = 0;
    const status_active = 1;
    const status_block = 2;
    const status_disable = 3;
    const status_not_confirm = 4;

    protected $table = 'articles';

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'latitude',
        'longitude',
        'city_id',
        'address',
        'short_address',
        'status',
        'gender_acceptance',
        'priority_show',
        'like_count',
        'dislike_count',
        'profit_share_percentage',
        'is_ball',
        'ball_price',
        'user_article_manager_id',
        'user_creator',
        'user_editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'price' => 'decimal:3',
        'latitude' => 'string',
        'longitude' => 'string',
        'city_id' => 'integer',
        'address' => 'string',
        'short_address' => 'string',
        'status' => 'integer',
        'gender_acceptance' => 'integer',
        'priority_show' => 'integer',
        'like_count' => 'integer',
        'dislike_count' => 'integer',
        'profit_share_percentage' => 'integer',
        'is_ball' => 'boolean',
        'ball_price' => 'decimal:3',
        'user_article_manager_id' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'userCreator',
        'userEditor',
        'city',
        'scores',
        'keywords',
        'categories',
        'images',
        'urlImages',
        'tags',
        'sports',
        'attributes',
        'reserveTemplates',
        'reserves',
        'userArticleManager'
    ];

    public function userArticleManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_article_manager_id', 'id');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            # user_creator
            if (is_null($item?->user_creator)) {
                $item->user_creator = set_user_creator();
            }
            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
        static::updating(function ($item) {
            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
    }

    public static function getStatusArticleTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusArticlePersian();
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
    public static function getStatusArticle(): array
    {
        return [
            self::status_unknown,
            self::status_active,
            self::status_block,
            self::status_disable,
            self::status_not_confirm,
        ];
    }

    public static function getStatusArticlePersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_active => 'فعال',
            self::status_block => 'بلاک شده',
            self::status_disable => 'غیرفعال شده',
            self::status_not_confirm => 'تایید نشده',
        ];
    }
    public static function like($article_id, $user_id = null, $type = 'like'): int
    {
        $user_id = $user_id ?? get_user_id_login();
        $type_count = $type . '_count';
        /** @var Article $article */
        $article = Article::query()->find($article_id);
        $fields = ['article_id' => $article_id];
        if ($user_id) {
            $fields['user_id'] = $user_id;
            if (!LikeArticle::query()->where($fields)->exists()) {
                LikeArticle::query()->create($fields);
            } else {
                return $article->$type_count;
            }
        } else {
            LikeArticle::query()->create($fields);
        }

        if ($type === 'like') {
            $article->increment('likes_count');
        } else {
            $article->increment('dislike_count');
        }

        return $article->$type_count;
    }
    public static function dislike($article_id, $type = 'dislike', $user_id = null): int
    {
        return self::like(article_id: $article_id, user_id: $user_id, type: $type);
    }
    public static function updateScore($article_id): float|int
    {
        /** @var Article $article */
        $article = Article::query()->find($article_id);
        $scores = $article->scores();
        $scores_sum = $scores->sum('score');
        $scores_count = $scores->count();
        $score_average = $scores_sum / $scores_count;
        $article->update(['score' => $score_average]);
        return $score_average;
    }

    // relations
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'article_id');
    }
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'article_keyword');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_article');
    }
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
    public function urlImages(): HasMany
    {
        return $this->hasMany(Image::class)->select('url', 'article_id');
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_article');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uer_id', 'id');
    }

}
