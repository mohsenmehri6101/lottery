<?php

namespace Modules\Authentication\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Authentication\Entities\UserDetail;

class UserDetailRepository extends BaseRepository
{
    public function model(): string
    {
        return UserDetail::class;
    }

    public function relations(): array
    {
        return UserDetail::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'name',
            'family',
            'father',
            'national_code',
            'address',
        ];
    }

}
