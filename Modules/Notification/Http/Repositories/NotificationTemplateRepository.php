<?php

namespace Modules\Notification\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Notification\Entities\NotificationTemplate;

class NotificationTemplateRepository extends BaseRepository
{
    public function model(): string
    {
        return NotificationTemplate::class;
    }

    public function relations(): array
    {
        return NotificationTemplate::$relations_;
    }
}
