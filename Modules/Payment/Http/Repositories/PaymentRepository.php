<?php

namespace Modules\Payment\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Payment\Entities\Payment;

class PaymentRepository extends BaseRepository
{
    public function model(): string
    {
        return Payment::class;
    }

    public function relations(): array
    {
        return Payment::$relations_;
    }

}
