<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $url
 * @property string $string_random
 */
class QrCode extends Model
{
    protected $table = 'qr_codes';

    protected $fillable = [
        'id',
        'url',
        'string_random',
    ];

    protected $casts = [
        'id' => 'integer',
        'url' => 'string',
        'string_random' => 'string',
    ];

    public static array $relations_ = [];

}
