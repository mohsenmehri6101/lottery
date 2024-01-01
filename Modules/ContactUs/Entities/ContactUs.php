<?php

namespace Modules\ContactUs\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $text
 * @property string $status
 * @property $created_at
 * @property $updated_at
 */
class ContactUs extends Model
{
    protected $table = 'contact_us';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'text',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'text' => 'string',
        'status' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $hidden = [];

    public static array $relations_ = [];

}
