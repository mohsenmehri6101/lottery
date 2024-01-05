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
use JetBrains\PhpStorm\ArrayShape;
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

    const payment_status_unknown = 0;
    const payment_status_unpaid = 1;
    const payment_status_paid = 2;

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
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'userCreator',
        'userEditor',
        'user',
        'reserveTemplate',
        'gym',
        'factors',
    ];

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

    public static function getPaymentStatusTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getPaymentStatusPersian();
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


    public static function getStatusTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getPaymentStatusPersian();
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

    public static function getPaymentStatus(): array
    {
        return [
            self::payment_status_unknown,
            self::payment_status_unpaid,
            self::payment_status_paid,
        ];
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }

    #[ArrayShape([self::payment_status_unknown => "string", self::payment_status_unpaid => "string", self::payment_status_paid => "string"])]
    public static function getPaymentStatusPersian(): array
    {
        return [
            self::payment_status_unknown => 'نامشخص',
            self::payment_status_unpaid => 'پرداخت نشده',
            self::payment_status_paid => 'پرداخت شده',
        ];
    }

    public static function getStatusPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_active => 'فعال',
            self::status_inactive => 'غیرفعال',
            self::status_blocked => 'بلاک شده',
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

    public static  function reserveBetweenDates($gym_id, $startDate = null, $endDate = null): Collection|array
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
}
