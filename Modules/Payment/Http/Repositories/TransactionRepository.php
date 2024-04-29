<?php

namespace Modules\Payment\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Payment\Entities\Transaction;

class TransactionRepository extends BaseRepository
{
    public function model(): string
    {
        return Transaction::class;
    }

    public function relations(): array
    {
        return Transaction::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'text',
        ];
    }

}
