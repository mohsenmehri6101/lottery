<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Gym;

class GymRepository extends BaseRepository
{
    public function model(): string
    {
        return Gym::class;
    }

    public function relations(): array
    {
        return Gym::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'name',
            'description',
            'address',
            'short_address',
        ];
    }

}
