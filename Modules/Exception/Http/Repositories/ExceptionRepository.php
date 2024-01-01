<?php

namespace Modules\Exception\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Exception\Entities\ExceptionModel;

class ExceptionRepository extends BaseRepository
{
    public function model(): string
    {
        return ExceptionModel::class;
    }

    public function relations(): array
    {
        return ExceptionModel::$relations_;
    }

}
