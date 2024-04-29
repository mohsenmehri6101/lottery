<?php

namespace Modules\Geographical\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Geographical\Entities\Province;

class ProvinceRepository extends BaseRepository
{
    public function model(): string
    {
        return Province::class;
    }

    public function relations(): array
    {
        return Province::$relations_ ?? [];
    }

    public function fillable_search(): array
    {
        return [
            'name',
        ];
    }

}
