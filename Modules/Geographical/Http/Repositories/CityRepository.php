<?php

namespace Modules\Geographical\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Geographical\Entities\City;

class CityRepository extends BaseRepository
{
    public function model(): string
    {
        return City::class;
    }

    public function relations(): array
    {
        return City::$relations_ ?? [];
    }

    public function fillable_search(): array
    {
        return [
            'name',
        ];
    }
}
