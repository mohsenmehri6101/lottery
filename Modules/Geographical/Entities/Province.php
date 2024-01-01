<?php

namespace Modules\Geographical\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property array $polygon
 * @property integer $status
 */
class Province extends Model
{
    use HasFactory;

    const status_unknown = 0;
    const status_inactive = 1;
    const status_active = 2;

    protected $table = 'provinces';

    public $timestamps = false;

    protected $fillable = ['name', 'polygon', 'status'];

    protected $casts = [
        'name' => 'string',
        'polygon' => 'array',
        'status' => 'integer',
    ];

    protected $hidden = ['polygon'];

    public static array $relations_ = [
        'cities',
    ];

    public static function getStatusCityTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusCityPersian();
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

    public static function getStatusCity(): array
    {
        return [
            self::status_unknown,
            self::status_inactive,
            self::status_active,
        ];
    }

    public static function getStatusCityPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_inactive => 'غیرفعال',
            self::status_active => 'فعال',
        ];
    }

    // relations
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

}
