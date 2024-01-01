<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $persian_name
 */
class Bank extends Model
{
    protected $table = 'banks';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'persian_name',
    ];

    public static array $relations_ = [];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'persian_name' => 'string',
    ];

}
