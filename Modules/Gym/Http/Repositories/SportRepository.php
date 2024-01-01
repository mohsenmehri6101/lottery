<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Sport;

class SportRepository extends BaseRepository
{
    public function model(): string
    {
        return Sport::class;
    }
    public function relations(): array
    {
        return Sport::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'name',
        ];
    }

}
