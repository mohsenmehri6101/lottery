<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Tag;

class TagRepository extends BaseRepository
{
    public function model(): string
    {
        return Tag::class;
    }

    public function relations(): array
    {
        return Tag::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'tag',
        ];
    }

}
