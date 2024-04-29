<?php

namespace Modules\Authorization\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Authorization\Entities\Role;

class RoleRepository extends BaseRepository
{
    public function model(): string
    {
        return Role::class;
    }

    public function relations(): array
    {
        return Role::$relations_;
    }

}
