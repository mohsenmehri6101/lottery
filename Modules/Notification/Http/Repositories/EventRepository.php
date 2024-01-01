<?php

namespace Modules\Notification\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Notification\Entities\Event;

class EventRepository extends BaseRepository
{
    public function model(): string
    {
        return Event::class;
    }

    public function relations(): array
    {
        return Event::$relations_;
    }

}
