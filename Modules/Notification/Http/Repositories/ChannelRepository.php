<?php

namespace Modules\Notification\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Notification\Entities\Channel;

class ChannelRepository extends BaseRepository
{
    public function model(): string
    {
        return Channel::class;
    }

    public function relations(): array
    {
        return Channel::$relations_;
    }
}
