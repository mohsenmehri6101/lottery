<?php

namespace Modules\Payment\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Payment\Entities\Bank;

class BankRepository extends BaseRepository
{
    public function model(): string
    {
        return Bank::class;
    }

    public function relations(): array
    {
        return Bank::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'name',
            'persian_name',
        ];
    }
}
