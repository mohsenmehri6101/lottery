<?php

namespace Modules\Faq\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property integer $id
 * @property string $question
 * @property string $answer
 * @property integer $order
 * @property integer $status
 * @property integer $user_creator
 * @property integer $user_editor
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class Faq extends Model
{
    use SoftDeletes, UserEditor, UserCreator;

    protected $table = 'faqs';

    const status_unknown = 0;
    const status_active = 1;
    const status_inactive = 2;

    protected $fillable = [
        'id',
        'question',
        'answer',
        'order',
        'status',
        'user_creator',
        'user_editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'question' => 'string',
        'answer' => 'string',
        'order' => 'integer',
        'status' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public static array $relations_ = [
        'userCreator',
        'userEditor',
    ];

    protected static function boot()
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
            self::status_active,
            self::status_inactive,
        ];
    }

    #[ArrayShape([self::status_unknown => "string", self::status_active => "string", self::status_inactive => "string"])]
    public static function getStatusPersian(): array
    {
        return [
            self::status_unknown => 'نامشخص',
            self::status_active => 'فعال',
            self::status_inactive => 'غیرفعال',
        ];
    }
}
