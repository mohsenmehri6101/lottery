<?php

namespace Modules\Slider\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Geographical\Entities\City;

/**
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $link
 * @property string $text
 * @property integer $status
 * @property integer $city_id
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Slider extends Model
{
    use SoftDeletes, UserCreator, UserEditor;

    const status_unknown = 0;
    const status_active = 1;
    const status_inactive = 2;

    protected $table = 'sliders';

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

    protected $fillable = [
        'id',
        'title',
        'image',
        'link',
        'text',
        'status',
        'city_id',
        'user_creator',
        'user_editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'image' => 'string',
        'link' => 'string',
        'text' => 'string',
        'status' => 'integer',
        'city_id' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public static array $relations_ = ['city'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public static function getStatusSlider(): array
    {
        return [
            self::status_unknown,
            self::status_active,
            self::status_inactive,
        ];
    }

    #[ArrayShape([self::status_unknown => "string", self::status_active => "string", self::status_inactive => "string"])]
    public static function getStatusSliderPersian(): array
    {
        return [
            self::status_unknown => 'مشخص نشده',
            self::status_active => 'فعال',
            self::status_inactive => 'غیرفعال',
        ];
    }

    public static function getStatusSliderTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusSliderPersian();
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

}
