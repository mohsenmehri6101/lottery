<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\ReserveTemplate;

class ReserveTemplateRepository extends BaseRepository
{
    public function model(): string
    {
        return ReserveTemplate::class;
    }

    public function relations(): array
    {
        return ReserveTemplate::$relations_;
    }

}
