<?php

namespace Modules\Authorization\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Authorization\Entities\Permission;

class PermissionRepository extends BaseRepository
{
    public function model(): string
    {
        return Permission::class;
    }

    public function relations(): array
    {
        return Permission::$relations_;
    }
}
