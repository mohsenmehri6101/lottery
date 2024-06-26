<?php

namespace Modules\Exception\Entities;

use App\Models\Traits\GetCastsModel;
use App\Models\Traits\UserCreator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $status_code
 * @property string $exception
 * @property string $message
 * @property integer $user_creator
 * @property $stack_trace
 * @property $requests
 * @property $headers
 * @property string $user_agent
 * @property $extra_date
 * @property Date $created_at
 * @property Date $updated_at
 * @property Date $deleted_at
 */
class Error extends Model
{
    use SoftDeletes, GetCastsModel, UserCreator;

    protected $table = 'errors';

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            # user_creator
            if (is_null($item->user_creator)) {
                $item->user_creator = set_user_creator();
            }
        });
    }

    protected $fillable = [
        'id',
        'url',
        'status_code',
        'exception',
        'message',
        'user_creator',
        'stack_trace',
        'requests',
        'headers',
        'user_agent',
        'extra_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'url' => 'string',
        'status_code' => 'integer',
        'exception' => 'string',
        'message' => 'string',
        'user_creator' => 'integer',

        'stack_trace' => 'array',
        'requests' => 'array',
        'headers' => 'array',
        'extra_date' => 'array',

        'user_agent' => 'string',
        'created_at' => 'date',
        'updated_at' => 'date',
        'deleted_at' => 'date',
    ];

    public function setAttribute($key, $value)
    {
        if (in_array($key, ['stack_trace', 'requests', 'headers', 'extra_date']) && is_array($value)) {
            $value = json_encode($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, ['stack_trace', 'requests', 'headers', 'extra_date']) && is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }

    protected $hidden = [
        # 'id'
    ];

    public static array $relations_ = [
        'exceptionModel',
        'userCreator'
    ];

    /**
     * @return BelongsTo
     */
    public function exceptionModel(): BelongsTo
    {
        return $this->belongsTo(ExceptionModel::class, 'exception', 'exception');
    }
}
