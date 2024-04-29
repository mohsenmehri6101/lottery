<?php

namespace Modules\Payment\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Payment\Entities\Account;

class AccountRepository extends BaseRepository
{
    public function model(): string
    {
        return Account::class;
    }

    public function relations(): array
    {
        return Account::$relations_;
    }

}
