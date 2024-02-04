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
    # ---------------------------------------------------------
    const SPECIFICATION_UNKNOWN = 0;
    const SPECIFICATION_DEBIT = 1;
    const SPECIFICATION_CREDIT = 2;
    # ---------------------------------------------------------
    const TRANSACTION_UNKNOWN = 0;
    const TRANSACTION_TYPE_WITHDRAWAL = 1;
    const TRANSACTION_TYPE_DEPOSIT = 2;
    # ---------------------------------------------------------
    const OPERATION_TYPE_UNKNOWN = 0;/* مشخص نشده */
    const OPERATION_TYPE_ASSIGN_TO_GYM_MANAGER = 1;/* تخصیص به مدیر سالن */
    const OPERATION_TYPE_PAYMENT_TO_GYM_MANAGER = 2;/* پرداخت به مدیر سالن */
    const OPERATION_TYPE_RESERVED_AMOUNT = 3;/* مبلغ رزرو شده */
    const OPERATION_TYPE_RETURN_TO_USER = 4;/* بازگشت مبلغ به کاربر */
    const OPERATION_TYPE_DEPOSIT_TO_WALLET = 5;/* واریز به کیف پول */
    const OPERATION_TYPE_WITHDRAWAL_FROM_WALLET = 6;/* برداشت از کیف پول */
    # ---------------------------------------------------------
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
        'description' => 'text',
        'specification' => 'integer',
        'transaction_type' => 'integer',
        'operation_type' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'timed_at' => 'timestamp',
    ];
    # ---------------------------------------------------------
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
}
