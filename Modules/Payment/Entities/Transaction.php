<?php

namespace Modules\Payment\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authentication\Entities\User;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, UserCreator, UserEditor;

    protected $table = 'transactions';
    const SPECIFICATION_UNKNOWN = 0;
    const SPECIFICATION_DEBIT = 1;
    const SPECIFICATION_CREDIT = 2;
    const TRANSACTION_TYPE_UNKNOWN = 0;
    const TRANSACTION_TYPE_WITHDRAWAL = 1;
    const TRANSACTION_TYPE_DEPOSIT = 2;
    const OPERATION_TYPE_UNKNOWN = 0;/* مشخص نشده */
    const OPERATION_TYPE_ASSIGN_TO_GYM_MANAGER = 1;/* تخصیص به مدیر سالن */
    const OPERATION_TYPE_PAYMENT_TO_GYM_MANAGER = 2;/* پرداخت به مدیر سالن */
    const OPERATION_TYPE_RESERVED_AMOUNT = 3;/* مبلغ رزرو شده */
    const OPERATION_TYPE_RETURN_TO_USER = 4;/* بازگشت مبلغ به کاربر */
    const OPERATION_TYPE_DEPOSIT_TO_WALLET = 5;/* واریز به کیف پول */
    const OPERATION_TYPE_WITHDRAWAL_FROM_WALLET = 6;/* برداشت از کیف پول */
    protected $fillable = [
        'user_destination',
        'user_resource',
        'price',
        'description',
        'specification',
        'transaction_type',
        'operation_type',
        'user_creator',
        'user_editor',
        'timed_at',
    ];
    protected $casts = [
        'user_destination' => 'integer',
        'user_resource' => 'integer',
        'price' => 'decimal:15',
        'description' => 'string',
        'specification' => 'integer',
        'transaction_type' => 'integer',
        'operation_type' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'timed_at' => 'timestamp',
    ];
    public static array $relations_ = [
        'userDestination',
        'userResource',
        'userCreator',
        'userEditor',
    ];
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            if (is_null($item->user_creator)) {
                $item->user_creator = set_user_creator();
            }
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
        static::updating(function ($item) {
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
    }
    public function userDestination(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_destination', 'id');
    }
    public function userResource(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_resource', 'id');
    }
    /* specification const methods */
    public static function getStatusSpecificationTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusSpecificationPersian();
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
    public static function getStatusSpecifications(): array
    {
        return [
            self::SPECIFICATION_UNKNOWN,
            self::SPECIFICATION_DEBIT,
            self::SPECIFICATION_CREDIT,
        ];
    }
    public static function getStatusSpecificationPersian(): array
    {
        return [
            self::SPECIFICATION_UNKNOWN => 'نامشخص',
            self::SPECIFICATION_DEBIT => 'debit',
            self::SPECIFICATION_CREDIT => 'credit',
        ];
    }
    /* specification const methods */
    /* transaction type const methods */
    public static function getStatusTransactionTypeTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusTransactionTypePersian();
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
    public static function getStatusTransactionTypes(): array
    {
        return [
            self::TRANSACTION_TYPE_UNKNOWN,
            self::TRANSACTION_TYPE_WITHDRAWAL,
            self::TRANSACTION_TYPE_DEPOSIT,
        ];
    }
    public static function getStatusTransactionTypePersian(): array
    {
        return [
            self::TRANSACTION_TYPE_UNKNOWN => 'نامشخص',
            self::TRANSACTION_TYPE_WITHDRAWAL => 'برداشت',
            self::TRANSACTION_TYPE_DEPOSIT => 'واریز',
        ];
    }
    /* transaction type const methods */
    /* operation type const methods */
    public static function getStatusOperationTypeTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusOperationTypePersian();
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
    public static function getStatusOperationTypes(): array
    {
        return [
            self::OPERATION_TYPE_UNKNOWN,
            self::OPERATION_TYPE_ASSIGN_TO_GYM_MANAGER,
            self::OPERATION_TYPE_PAYMENT_TO_GYM_MANAGER,
            self::OPERATION_TYPE_RESERVED_AMOUNT,
            self::OPERATION_TYPE_RETURN_TO_USER,
            self::OPERATION_TYPE_DEPOSIT_TO_WALLET,
            self::OPERATION_TYPE_WITHDRAWAL_FROM_WALLET,
        ];
    }
    public static function getStatusOperationTypePersian(): array
    {
        return [
            self::OPERATION_TYPE_UNKNOWN => 'نامشخص',
            self::OPERATION_TYPE_ASSIGN_TO_GYM_MANAGER => 'تخصیص به مدیر سالن',
            self::OPERATION_TYPE_PAYMENT_TO_GYM_MANAGER => 'پرداخت به مدیر سالن',
            self::OPERATION_TYPE_RESERVED_AMOUNT => 'مبلغ رزرو شده',
            self::OPERATION_TYPE_RETURN_TO_USER => 'بازگشت مبلغ به کاربر',
            self::OPERATION_TYPE_DEPOSIT_TO_WALLET => 'واریز به کیف پول',
            self::OPERATION_TYPE_WITHDRAWAL_FROM_WALLET => 'برداشت از کیف پول',
        ];
    }
    /* operation type const methods */

}
