<?php

namespace Modules\Notification\Services;

use Illuminate\Routing\Controller;
use Modules\Authentication\Entities\User;
use Modules\Notification\Http\Repositories\EventRepository;
use Modules\Notification\Http\Requests\Event\EventMeRequest;
use Exception;

class EventService extends Controller
{
    public function __construct(public EventRepository $eventRepository)
    {
    }

    public function myEvents(EventMeRequest $request)
    {
        try {
            $fields = $request->validated();

            /**
             * @var string $name
             * @var string $title
             * @var string $tag
             * @var string $description
             * @var integer $priority
             * @var integer $notification_template_id
             * @var $pivot
             * @var $selects_pivot
             */
            extract($fields);

            unset($fields['pivot'], $fields['selects_pivot'], $fields['selects']);

            /** @var User $user */
            $user = get_user_login();
            $columns_pivot = ['channel_id', 'event_id', 'user_id', 'status'];
            $selects_pivot = $selects_pivot ?? $columns_pivot;

            # todo query in events table notImplement yet
            $events_from_user = $user->events();
            $events_from_user->when(count($fields) > 0, function ($queryInEvent) use ($fields) {
                /** @var EventRepository $eventRepository */
                $eventRepository = resolve('EventRepository');
                return $eventRepository->queryByInputs(query: $queryInEvent, inputs: $fields);
            });

            return $events_from_user->withPivot($selects_pivot)->get() ?? [];

        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
