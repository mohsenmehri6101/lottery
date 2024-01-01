<?php

namespace Modules\Exception\Entities;

use App\Models\Traits\GetCastsModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
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
    use SoftDeletes,GetCastsModel;

    protected $table='errors';

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

    protected $fillable=[
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

    protected $casts=[
        'id'=>'integer',

        'url'=>'string',
        'status_code'=>'integer',
        'exception'=>'string',
        'message'=>'string',
        'user_creator'=>'integer',
        'stack_trace'=>'json',
        'requests'=>'json',
        'headers'=>'json',
        'user_agent'=>'string',
        'extra_date'=>'json',

        'created_at'=>'date',
        'updated_at'=>'date',
        'deleted_at'=>'date',
    ];

    protected $hidden=[
        'id'
    ];

    public static array $relations_=[
        'exceptionModel'
    ];

    /**
     * @return BelongsTo
     */
    public function exceptionModel(): BelongsTo
    {
        return $this->belongsTo(ExceptionModel::class,'exception','exception');
    }
}
