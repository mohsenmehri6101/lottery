<?php

namespace Modules\Gym\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authentication\Entities\User;
use Modules\Payment\Entities\Factor;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property integer $status
 * @property integer $user_creator
 * @property integer $user_editor
 * @property integer $factor_id
 * @property integer $gym_id
 * @property integer $reserve_id
 * @property integer $reserve_template_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Complaint extends Model
{
    use UserEditor;
    use UserCreator;
    use SoftDeletes;

    protected $table = 'complaints';

    const status_unknown = 0;
    const status_not_checked = 1;
    const status_reviewed = 2;

    protected $fillable = [
        'id',
        'user_id',
        'description',
        'status',
        'user_creator',
        'user_editor',
        'factor_id',
        'gym_id',
        'reserve_id',
        'reserve_template_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'description' => 'integer',
        'status' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'factor_id' => 'integer',
        'gym_id' => 'integer',
        'reserve_id' => 'integer',
        'reserve_template_id' => 'integer',
        'common_complaint_id' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'user',
        'userCreator',
        'userEditor',
        'factor',
        'gym',
        'reserve',
        'reserveTemplate',
        'commonComplaint',
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

    public function gym(): HasOne
    {
        return $this->hasOne(Gym::class, 'id','gym_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function factor(): HasOne
    {
        return $this->hasOne(Factor::class,'id', 'factor_id');
    }

    public function reserve(): HasOne
    {
        return $this->hasOne(Reserve::class,'id', 'reserve_id');
    }

    public function reserveTemplate(): HasOne
    {
        return $this->hasOne(Reserve::class,'id', 'reserve_template_id');
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
            self::status_unknown,
            self::status_not_checked,
            self::status_reviewed,
        ];
    }

    public static function getStatusPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_not_checked => 'بررسی نشده',
            self::status_reviewed => 'بررسی شده',
        ];
    }

    public function commonComplaint(): BelongsTo
    {
        return $this->belongsTo(CommonComplaint::class);
    }
}
