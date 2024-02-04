<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Authentication\Entities\User;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'user_destination',
        'user_resource',
        'price',
        'description',
        'specification',
        'transaction_type',
        'operation_type',
        'user_creator',
        'user_editor',
        'timed_at',
    ];

    protected $casts = [
        'user_destination' => 'integer',
        'user_resource' => 'integer',
        'price' => 'decimal:3',
        'description' => 'text',
        'specification' => 'integer',
        'transaction_type' => 'integer',
        'operation_type' => 'integer',
        'user_creator' => 'integer',
        'user_editor' => 'integer',
        'timed_at' => 'timestamp',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($item) {
            if (is_null($item->user_creator)) {
                $item->user_creator = set_user_creator();
            }

            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });

        static::updating(function ($item) {
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
    }

    public function userDestination(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_destination', 'id');
    }

    public function userResource(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_resource', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
