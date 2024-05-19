<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Reserve;

class ReserveRepository extends BaseRepository
{
    public function model(): string
    {
        return Reserve::class;
    }

    public function relations(): array
    {
        return Reserve::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'tracking_code',
        ];
    }
}
