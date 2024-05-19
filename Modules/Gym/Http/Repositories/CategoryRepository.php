<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Category;

class CategoryRepository extends BaseRepository
{
    public function model(): string
    {
        return Category::class;
    }

    public function relations(): array
    {
        return Category::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'name',
        ];
    }

}
