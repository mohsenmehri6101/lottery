<?php

namespace Modules\Article\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Article\Entities\Keyword;

class KeywordRepository extends BaseRepository
{
    public function model(): string
    {
        return Keyword::class;
    }
    public function relations(): array
    {
        return Keyword::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'keyword',
        ];
    }

}
