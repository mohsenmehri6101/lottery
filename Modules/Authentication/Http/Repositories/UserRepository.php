<?php

namespace Modules\Authentication\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Authentication\Entities\User;

class UserRepository extends BaseRepository
{
    public function model(): string
    {
        return User::class;
    }

    public function relations(): array
    {
        return User::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'username',
            'email',
            'mobile',
        ];
    }
}
