<?php

namespace Modules\Authentication\Entities;

use App\Models\ParentModel;
use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Geographical\Entities\City;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $family
 * @property string $father
 * @property string $national_code
 * @property $birthday
 * @property integer $gender
 * @property string $latitude
 * @property string $longitude
 * @property integer $city_id
 * @property string $address
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class UserDetail extends ParentModel
{
    use UserCreator, UserEditor, SoftDeletes;

    const gender_unknown = 0;
    const gender_male = 1;
    const gender_female = 2;

    protected $table = 'user_details';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'family',
        'father',
        'national_code',
        'birthday',
        'gender',
        'latitude',
        'longitude',
        'city_id',
        'address',
        'user_creator',
        'user_editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'name' => 'string',
        'family' => 'string',
        'father' => 'string',
        'national_code' => 'string',
        'birthday' => 'string',
        'gender' => 'integer',
        'latitude' => 'string',
        'longitude' => 'string',
        'city_id' => 'integer',
        'address' => 'string',
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
        'city',
    ];

    protected static function boot()
    {
        parent::boot();
        // todo creating or updating is bad
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

    public static function getStatusGender(): array
    {
        return [
            self::gender_unknown,
            self::gender_male,
            self::gender_female,
        ];
    }

    public static function getStatusGenderPersian(): array
    {
        return [
            self::gender_unknown => 'نامشخص',
            self::gender_male => 'مرد',
            self::gender_female => 'زن',
        ];
    }

    public static function getStatusGenderTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusGenderPersian();
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

    # relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    # relations
}
