<?php

namespace Modules\Payment\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Payment\Entities\Factor;
class FactorRepository extends BaseRepository
{
    public function model(): string
    {
        return Factor::class;
    }

    public function relations(): array
    {
        return Factor::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'description',
        ];
    }

}
