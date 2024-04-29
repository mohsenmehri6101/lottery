<?php

namespace Modules\Notification\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Notification\Entities\Notification;

class NotificationRepository extends BaseRepository
{
    public function model(): string
    {
        return Notification::class;
    }

    public function relations(): array
    {
        return Notification::$relations_;
    }

}
