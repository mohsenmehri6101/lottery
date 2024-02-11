<?php

namespace Modules\Payment\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Authentication\Entities\User;

/**
 * @property integer $id
 * @property integer $status
 * @property string $resnumber
 * @property $amount
 * @property integer $factor_id
 * @property integer $user_id
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Payment extends Model
{
    use UserCreator;
    use UserEditor;
    use SoftDeletes;

    protected $table = 'payments';

    const status_unknown = 0;
    const status_unpaid = 1;
    const status_paid = 2;
    const status_cancel = 3;

    protected $fillable = [
        'id',

        'status',
        'resnumber',
        'amount',

        'factor_id',
        'user_id',
        'user_creator',
        'user_editor',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',

        'status' => 'integer',
        'resnumber' => 'string',
        'amount' => 'decimal:3',

        'factor_id' => 'integer',
        'user_id' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',

        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public static array $relations_ = [
        'userCreator',
        'userEditor',
        'user',
        'factor',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class, 'factor_id', 'id');
    }

    public static function getStatusPaymentTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusPaymentPersian();
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

    public static function getStatusPayment(): array
    {
        return [
            self::status_unknown,# 0
            self::status_unpaid,# 1
            self::status_paid,# 2
            self::status_cancel,# 3
        ];
    }

    public static function getStatusPaymentPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',# 0
            self::status_unpaid => 'پرداخت نشده',# 1
            self::status_paid => 'پرداخت شده',# 2
            self::status_cancel => 'لغوشده',# 3
        ];
    }

    public static function resnumberUnique(): string
    {
        $resnumber = Str::random();
        while (self::query()->where('resnumber', $resnumber)->exists()) {
            $resnumber = Str::random();
        }
        return $resnumber;
    }
}
