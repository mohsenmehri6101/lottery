<?php

namespace Modules\Config\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Config\Entities\Config;

class ConfigRepository extends BaseRepository
{
    public function model(): string
    {
        return Config::class;
    }

    public function relations(): array
    {
        return Config::$relations_;
    }
}
