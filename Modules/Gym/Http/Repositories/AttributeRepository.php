<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Attribute;

class AttributeRepository extends BaseRepository
{
    public function model(): string
    {
        return Attribute::class;
    }

    public function relations(): array
    {
        return Attribute::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'name',
        ];
    }

}
