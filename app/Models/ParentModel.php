<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}

# in order
# use traits
# table name
# statuses
# fillable
# casts
# hidden
# relations static public
# boot
# self relations
