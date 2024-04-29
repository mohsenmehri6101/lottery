<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentication\Entities\User;

trait UserCreator
{
    public function userCreator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_creator', 'id');
    }
}
