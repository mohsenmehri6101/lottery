<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Log;
use MatanYadaev\EloquentSpatial\Objects\Point;

Trait SpacialEloquent
{

    public function scopeWithinRadius($query,$geometryColumn, Point $point, $radius = 50)
    {
        $query->whereRaw("ST_Distance_Sphere($geometryColumn, POINT(?, ?)) <= ?", [$point->longitude, $point->latitude, $radius]);
    }

    public function scopeEqualsPoint($query, $geometryColumn, $geometry)
    {
        return $query->whereRaw("$geometryColumn = ST_GEOMFROMTEXT('$geometry')");
    }
}
