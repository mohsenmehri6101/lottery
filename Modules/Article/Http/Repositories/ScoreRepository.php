<?php

namespace Modules\Article\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Article\Entities\Score;

class ScoreRepository extends BaseRepository
{
    public function model(): string
    {
        return Score::class;
    }
    public function relations(): array
    {
        return Score::$relations_;
    }

}
