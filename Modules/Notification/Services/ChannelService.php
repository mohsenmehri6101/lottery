<?php

namespace Modules\Notification\Services;

use App\Http\Controllers\Controller;
use Modules\Authentication\Services\UserService;
use Modules\Notification\Entities\Channel;
use Modules\Notification\Entities\Event;
use Modules\Notification\Http\Repositories\ChannelRepository;
use Modules\Notification\Http\Repositories\EventRepository;

class ChannelService extends Controller
{
    public function __construct(public ChannelRepository $channelRepository, EventRepository $eventRepository)
    {
    }

    public static function createUserSetTableChannelUser($user)
    {
        $user = UserService::getUser($user);
        /** @var EventRepository $eventRepository */
        $eventRepository = resolve('EventRepository');

        /** @var ChannelRepository $channelRepository */
        $channelRepository = resolve('ChannelRepository');
        $events = $eventRepository->all() ?? [];
        $channels = $channelRepository->all() ?? [];
        $events = collect($events);
        $channels = collect($channels);
        $events->each(function (Event $event) use ($user, $channels) {
            $channels->each(function (Channel $channel) use ($event, $user) {
                $check = $user->events()
                    ->wherePivot('channel_id', $channel->id)
                    ->wherePivot('event_id', $event->id);

                if ($check->doesntExist()) {
                    $user->events()->attach($event->id, [
                        'channel_id' => $channel->id,
                    ]);
                }
            });

        });
    }

}
