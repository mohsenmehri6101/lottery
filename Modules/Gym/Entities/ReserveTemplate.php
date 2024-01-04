<?php

namespace Modules\Gym\Entities;

use App\Models\Traits\GetCastsModel;
use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property integer $gym_id
 * @property integer $week_number
 * @property string $price
 * @property boolean $cod
 * @property boolean $is_ball
 * @property boolean $gender_acceptance
 * @property boolean $discount
 * @property integer $status
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class ReserveTemplate extends Model
{
    use SoftDeletes, GetCastsModel, UserCreator, UserEditor;

    protected $table = 'reserve_templates';
    const status_gender_acceptance_unknown = 0;
    const status_gender_acceptance_male = 1;
    const status_gender_acceptance_female = 2;
    const status_gender_acceptance_all = 3;

    const status_inactive=0;
    const status_active=1;

    protected $fillable = [
        'id',
        'from',
        'to',
        'gym_id',
        'week_number',
        'price',
        'cod',
        'is_ball',
        'gender_acceptance',
        'discount',
        'status',
        'user_creator',
        'user_editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'from' => 'datetime:H:i:s',
        'to' => 'datetime:H:i:s',
        'gym_id' => 'integer',
        'week_number' => 'integer',
        'price' => 'decimal:3',
        'cod' => 'boolean',
        'is_ball' => 'boolean',
        'gender_acceptance' => 'integer',
        'discount' => 'integer',
        'status' => 'integer',
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
        'user',
        'gym',
        'reserves',
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

    public static function getStatusGenderAcceptanceTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusGenderAcceptancePersian();
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
    public static function getStatusGenderAcceptance(): array
    {
        return [
            self::status_gender_acceptance_unknown,
            self::status_gender_acceptance_male,
            self::status_gender_acceptance_female,
            self::status_gender_acceptance_all,
        ];
    }
    public static function getStatusGenderAcceptancePersian(): array
    {
        return [
            self::status_gender_acceptance_unknown => 'نامشخص',# 0
            self::status_gender_acceptance_male => 'مرد',# 1
            self::status_gender_acceptance_female => 'زن',# 2
            self::status_gender_acceptance_all => 'زن و مرد',# 3
        ];
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
    public static function getStatus(): array
    {
        return [
            self::status_inactive,
            self::status_active,
        ];
    }
    public static function getStatusPersian(): array
    {
        return [
            self::status_inactive => 'غیرفعال',# 0
            self::status_active => 'فعال',# 1
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }

    public function reserves(): HasMany
    {
        return $this->hasMany(Reserve::class, 'reserve_template_id');
    }

}
