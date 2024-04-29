<?php

namespace Modules\Article\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Article\Entities\Article;

class ArticleRepository extends BaseRepository
{
    public function model(): string
    {
        return Article::class;
    }

    public function relations(): array
    {
        return Article::$relations_;
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
