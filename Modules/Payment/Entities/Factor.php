<?php

namespace Modules\Payment\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authentication\Entities\User;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Entities\Reserve;
use Modules\Gym\Entities\ReserveTemplate;

/**
 * @property integer $id
 * @property string $code
 * @property integer $reserve_id
 * @property string $total_price
 * @property string $total_price_vat
 * @property string $description
 * @property string $description_short
 * @property integer $status
 * @property integer $user_id
 * @property integer $gym_id
 * @property integer $payment_id_paid
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Factor extends Model
{
    use SoftDeletes, UserEditor, UserCreator;

    protected $table = 'factors';

    const status_unknown = 0;
    const status_unpaid = 1;
    const status_paid = 2;
    const status_cancel = 3;

    protected $fillable = [
        'id',
        'code',
        'total_price',
        'description',
        'status',
        'user_id',
        'gym_id',
        'payment_id_paid',
        'user_creator',
        'user_editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'total_price' => 'decimal:3',
        'description' => 'string',
        'status' => 'integer',
        'user_id' => 'integer',
        'gym_id' => 'integer',
        'payment_id_paid' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $appends = [
        'description_short',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'userCreator',
        'userEditor',
        'payments',
        'paymentPaid',
        'user',
        'reserves',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($factor) {
            # user_creator
            if (is_null($factor?->user_creator)) {
                $factor->user_creator = set_user_creator();
            }

            # user_editor
            if (is_null($factor->user_editor)) {
                $factor->user_editor = set_user_creator();
            }

            # code
            $factor->code = self::generate_factor_random_code($factor?->code ?? null);

            # description
            if (!filled($factor->description)) {
                $factor->description = self::calculateDescription($factor->reserves()->get());
            }

            # total_price
            if (!filled($factor->total_price)) {
                $factor->total_price = self::calculatePriceForFactor($factor);
            }

        });
        static::updating(function ($item) {
            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
    }

    public static function generate_factor_random_code($code = null)
    {
        if (is_null($code) || !filled($code)) {
            $min_random_code_factor = config('configs.payment.factor.min_random_code_factor');
            $max_random_code_factor = config('configs.payment.factor.max_random_code_factor');
            // ایجاد عدد رندوم
            $random_number = rand($min_random_code_factor, $max_random_code_factor);
            // ایجاد تاریخ با فرمت مورد نظر
            $date_prefix = now()->format('Ymd');
            // ترکیب تاریخ و عدد رندوم
            $code = $date_prefix . $random_number;
        }
        // بررسی تکراری بودن کد
        $code_is_repeat = self::query()->where('code', $code)->exists();
        // در صورت تکرار، متد را دوباره فراخوانی کرده تا کد جدید تولید شود
        return $code_is_repeat ? self::generate_factor_random_code() : $code;
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
            self::status_unknown,# 0
            self::status_unpaid,# 1
            self::status_paid,# 2
            self::status_cancel,# 3
        ];
    }

    public static function getStatusPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',# 0
            self::status_unpaid => 'پرداخت نشده',# 1
            self::status_paid => 'پرداخت شده',# 2
            self::status_cancel => 'لغوشده',# 3
        ];
    }

    public function reserves(): BelongsToMany
    {
        return $this->belongsToMany(Reserve::class)->withPivot('price');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getDescriptionShortAttribute(): array|string|null
    {
        // اینجا می‌توانید از regex استفاده کرده و بخش "شناسه رزرو:..." را حذف کنید
        $description = $this->attributes['description'] || '';
        $description = filled($description) ? $description : '';
        // اینجا از regex استفاده می‌کنیم تا بخش "شناسه رزرو:..." را از متن حذف کنیم
        $descriptionShort = preg_replace('/شناسه رزرو:\s*\d+\s*,\s*/', '', $description);
        return $descriptionShort;
    }
    public function paymentPaid(): HasOne
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id_paid');
    }

    public static function calculatePriceForFactor(Factor $factor): float|int|string
    {
        $totalPrice = 0;
        foreach ($factor->reserves as $reserve) {
            $reserveTemplate = $reserve->reserveTemplate;
            $gym = $reserve->gym;
            $price = $reserveTemplate->price ?? $gym->price;

            if ($reserveTemplate->discount > 0 && $reserveTemplate->discount <= 100) {
                $discountedPrice = $price * (1 - $reserveTemplate->discount / 100);
                $price = $discountedPrice;
            }

            if ($reserve->want_ball && $reserveTemplate->is_ball) {
                $price += $gym->ball_price;
            }

            $totalPrice += $price;
        }
        return $totalPrice;
    }

    public static function calculateDescription(Factor $factor): string
    {
        $description = "فاکتور مربوط به ";
        foreach ($factor->reserves as $reserve) {
            $gymName = $reserve->reserveTemplate->gym->name;
            $reserveId = $reserve->id;
            $reserveDate = $reserve->dated_at->format('Y-m-d');
            $discount = $reserve->reserveTemplate->discount;
            $ballStatus = $reserve->want_ball ? 'بله' : 'خیر';
            $ballPrice = $reserve->reserveTemplate->gym->ball_price;

            $userInfo = $reserve->user ? ($reserve->user->name && $reserve->user->family ?
                "({$reserve->user->name} {$reserve->user->family}, {$reserve->user->mobile})" :
                "({$reserve->user->mobile})") : '';

            $description .= "{$gymName}{$userInfo} (شناسه رزرو: {$reserveId}, تاریخ: {$reserveDate}, تخفیف: {$discount}%, توپ: {$ballStatus}, قیمت توپ: {$ballPrice}), ";
        }
        $description .= "با مجموع قیمت: {$factor->total_price}";
        return $description;
    }

}
