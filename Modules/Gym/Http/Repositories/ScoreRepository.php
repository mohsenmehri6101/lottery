<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Score;

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
