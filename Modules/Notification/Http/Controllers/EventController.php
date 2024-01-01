<?php

namespace Modules\Notification\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Notification\Http\Requests\Event\EventMeRequest;
use Modules\Notification\Services\EventService;

class EventController extends Controller
{
    public function __construct(public EventService $eventService)
    {
    }

    /**
     * @OA\Get  (
     *     path="/api/v1/events/my-events",
     *     tags={"notifications"},
     *     summary="store notification",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="string"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="title",in="query",required=false, @OA\Schema(type="string"),description="title"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="tag"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="priority",in="query",required=false, @OA\Schema(type="integer"),description="priority"),
     *     @OA\Parameter(name="notification_template_id",in="query",required=false, @OA\Schema(type="integer"),description="notification_template_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myEvents(EventMeRequest $request): JsonResponse
    {
        $events = $this->eventService->myEvents($request);
        return ResponseHelper::responseSuccess(['events' => $events]);
    }

}
