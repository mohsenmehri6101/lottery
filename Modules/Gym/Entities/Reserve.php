<?php

namespace Modules\Gym\Entities;

use App\Models\Traits\GetCastsModel;
use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authentication\Entities\User;
use Modules\Payment\Entities\Factor;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property integer $reserve_template_id
 * @property integer $gym_id
 * @property integer $user_id
 * @property integer $payment_status
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $dated_at
 * @property integer $want_ball
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Reserve extends Model
{

    use SoftDeletes, GetCastsModel, UserCreator, UserEditor;

    const status_unknown = 0;
    const status_active = 1;
    const status_inactive = 2;
    const status_blocked = 3;
    const status_reserving = 4;
    const status_reserved = 5;

    protected $table = 'reserves';

    protected $fillable = [
        'id',
        'reserve_template_id',
        'gym_id',
        'user_id',
        'payment_status',
        'user_creator',
        'user_editor',
        'dated_at',
        'status',
        'want_ball',
        'reserved_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'reserve_template_id' => 'integer',
        'gym_id' => 'integer',
        'user_id' => 'integer',
        'payment_status' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'dated_at' => 'datetime',
        'status' => 'integer',
        'want_ball' => 'boolean',
        'reserved_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $hidden = [];

    public $appends = [
        'dated_at_persian'
    ];

    public static array $relations_ = [
        'userCreator',
        'userEditor',
        'user',
        'reserveTemplate',
        'gym',
        'factors',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
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

    public static function getStatusTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusPersian();
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

    public static function getStatusPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_active => 'فعال',
            self::status_inactive => 'غیرفعال',
            self::status_blocked => 'بلاک شده',
            self::status_reserving => 'در حال رزرو',
            self::status_reserved => 'رزرو شده',
        ];
    }

    public static function getStatus(): array
    {
        return [
            self::status_unknown,
            self::status_active,
            self::status_inactive,
            self::status_blocked,
            self::status_reserving,
            self::status_reserved,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reserveTemplate(): BelongsTo
    {
        return $this->belongsTo(ReserveTemplate::class, 'reserve_template_id');
    }

    public function factors(): BelongsToMany
    {
        return $this->belongsToMany(Factor::class)->withPivot('price');
    }

    public static function reserveBetweenDates($gym_id, $startDate = null, $endDate = null): Collection|array
    {
        $query = self::query()->where('gym_id', $gym_id);

        if ($startDate === null) {
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->subWeek(4)->format('Y-m-d');
        }

        if ($endDate === null) {
            $endDate = Carbon::now()->format('Y-m-d');
        }
        $query->whereDate('dated_at', '>=', $startDate)->whereDate('dated_at', '<=', $endDate);

        return $query->get();
    }

    public function attributePrices(): BelongsToMany
    {
        return $this->belongsToMany(AttributePrice::class, 'attribute_gym_price_reserve');
    }

    public function getDatedAtPersianAttribute(): string
    {
        return verta($this->dated_at)->format('Y-m-d');
    }
}
