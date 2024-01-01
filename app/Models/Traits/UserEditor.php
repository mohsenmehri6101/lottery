<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentication\Entities\User;

trait UserEditor
{
    public function userEditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_editor', 'id');
    }
}
