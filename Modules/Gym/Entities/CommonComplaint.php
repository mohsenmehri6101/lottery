<?php

namespace Modules\Gym\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $text
 */
class CommonComplaint extends Model
{
    protected $table = 'common_complaints';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'text',
    ];

    protected $casts = [
        'id' => 'integer',
        'text' => 'string',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'complaints',
    ];

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

}
