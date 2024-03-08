<?php

namespace Modules\Gym\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
 * @property string $reason_gym_disabled
 * @property integer $score
 * @property integer $status
 * @property integer $gender_acceptance
 * @property integer $priority_show
 * @property integer $like_count
 * @property integer $dislike_count
 * @property integer $profit_share_percentage
 * @property integer $user_gym_manager_id
 * @property boolean $is_ball
 * @property string $ball_price
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Gym extends Model
{
    use SoftDeletes, UserCreator, UserEditor;
    const status_unknown = 0;

    const status_active = 1;
    const status_block = 2;
    const status_disable = 3;
    const status_not_confirm = 4;

    protected $table = 'gyms';

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
        'user_gym_manager_id',
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
        'user_gym_manager_id' => 'integer',
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
        'userGymManager'
    ];


    public function userGymManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_gym_manager_id', 'id');
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

    public static function getStatusGymTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusGymPersian();
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
    public static function getStatusGym(): array
    {
        return [
            self::status_unknown,
            self::status_active,
            self::status_block,
            self::status_disable,
            self::status_not_confirm,
        ];
    }

    public static function getStatusGymPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_active => 'فعال',
            self::status_block => 'بلاک شده',
            self::status_disable => 'غیرفعال شده',
            self::status_not_confirm => 'تایید نشده',
        ];
    }
    public static function like($gym_id, $user_id = null, $type = 'like'): int
    {
        $user_id = $user_id ?? get_user_id_login();
        $type_count = $type . '_count';
        /** @var Gym $gym */
        $gym = Gym::query()->find($gym_id);
        $fields = ['gym_id' => $gym_id];
        if ($user_id) {
            $fields['user_id'] = $user_id;
            if (!LikeGym::query()->where($fields)->exists()) {
                LikeGym::query()->create($fields);
            } else {
                return $gym->$type_count;
            }
        } else {
            LikeGym::query()->create($fields);
        }

        if ($type === 'like') {
            $gym->increment('likes_count');
        } else {
            $gym->increment('dislike_count');
        }

        return $gym->$type_count;
    }
    public static function dislike($gym_id, $type = 'dislike', $user_id = null): int
    {
        return self::like(gym_id: $gym_id, user_id: $user_id, type: $type);
    }
    public static function updateScore($gym_id): float|int
    {
        /** @var Gym $gym */
        $gym = Gym::query()->find($gym_id);
        $scores = $gym->scores();
        $scores_sum = $scores->sum('score');
        $scores_count = $scores->count();
        $score_average = $scores_sum / $scores_count;
        $gym->update(['score' => $score_average]);
        return $score_average;
    }

    // relations
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'gym_id');
    }
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'gym_keyword');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_gym');
    }
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
    public function urlImages(): HasMany
    {
        return $this->hasMany(Image::class)->select('url', 'gym_id');
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'gym_tag');
    }
    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class, 'sport_gym');
    }
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_gym');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function reserves(): HasMany
    {
        return $this->hasMany(Reserve::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uer_id', 'id');
    }
    public function reserveTemplates(): HasMany
    {
        return $this->hasMany(ReserveTemplate::class)->orderBy('week_number');
    }
    public function reserveTemplatesBetweenDates($startDate = null, $endDate = null): HasMany
    {
        if ($startDate === null) {
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->subWeek(4)->format('Y-m-d');
        }

        if ($endDate === null) {
            $endDate = Carbon::now()->format('Y-m-d');
        }

        return $this->hasMany(ReserveTemplate::class, 'gym_id')
            ->leftJoin('reserves', function ($join) use ($startDate, $endDate) {
                $join->on('reserve_templates.id', '=', 'reserves.reserve_template_id')
                    ->whereBetween('reserves.dated_at', [$startDate, $endDate]);
            })
            ->select('reserve_templates.*')
            ->addSelect('reserves.dated_at as reserve_dated_at')
            ->addSelect('reserves.user_id as reserve_user_id')
            ->addSelect('reserves.id as reserve_id');
    }
    public static function getReserveTemplateBetweenDate($gym_id, $from = null, $to = null): Collection
    {
        if ($from === null) {
            $now = Carbon::now();
            $from = $now->startOfWeek()->subWeek(4)->format('Y-m-d');
        }

        if ($to === null) {
            $to = Carbon::now()->format('Y-m-d');
        }

        return DB::table('reserve_templates')
            ->select([
                'reserve_templates.id',
                'reserve_templates.from',
                'reserve_templates.to',
                'reserve_templates.gym_id',
                'reserve_templates.week_number as week_number',
                'reserves.dated_at as reserve_dated_at',
                'reserves.user_id as reserve_user_id',
                'reserves.id as reserve_id',
            ])
            ->leftJoin('reserves', 'reserves.reserve_template_id', '=', 'reserve_templates.id')
            ->where('reserve_templates.gym_id', $gym_id)
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('reserves.dated_at', [$from, $to])
                    ->orWhereNull('reserves.dated_at');
            })->get();
    }
}
