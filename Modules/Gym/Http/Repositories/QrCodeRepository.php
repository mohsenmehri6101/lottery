<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\QrCode;

class QrCodeRepository extends BaseRepository
{
    public function model(): string
    {
        return QrCode::class;
    }

    public function relations(): array
    {
        return QrCode::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'url',
            'string_random',
        ];
    }

}
