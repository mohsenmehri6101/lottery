<?php

namespace Modules\Article\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Article\Entities\ReserveTemplate;

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
