<?php

namespace Modules\Exception\Entities;

use App\Models\Traits\GetCastsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @property $id
 * @property $exception
 * @property $status_code
 * @property string $message
 * @property $level
 * @property $description
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class ExceptionModel extends Model
{
    use SoftDeletes, GetCastsModel;

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            if (is_null($item?->message) || !filled($item?->message)) {
                $item->message = trans('custom.defaults.exceptions.500');
            }
        });
    }

    /**
     * @var string
     */
    protected $table = 'exceptions';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'exception',
        'message',
        'level',
        'status_code',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'id' => 'integer',
        'level' => 'integer',
        'status_code' => 'integer',
        'exception' => 'string',
        'description' => 'string',
        'message' => 'string',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [];

    public static array $relations_=[
        'errors'
    ];

    public function errors(): HasMany
    {
        return $this->hasMany(Error::class, 'exception', 'exception');
    }
}
