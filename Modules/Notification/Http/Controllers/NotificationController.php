<?php

namespace Modules\Notification\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Notification\Http\Requests\Notification\NotificationStoreRequest;
use Modules\Notification\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(public NotificationService $notificationService)
    {
    }

    /**
     * @OA\Post (
     *     path="/api/v1/notifications",
     *     tags={"notifications"},
     *     summary="store notification",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="title",in="query",required=false, @OA\Schema(type="string"),description="title"),
     *     @OA\Parameter(name="text",in="query",required=false, @OA\Schema(type="string"),description="text"),
     *     @OA\Parameter(name="send_at",in="query",required=false, @OA\Schema(type="string"),description="send_at"),
     *     @OA\Parameter(name="priority",in="query",required=false, @OA\Schema(type="string"),description="priority"),
     *     @OA\Parameter(name="permission_id",in="query",required=false, @OA\Schema(type="string"),description="permission_id"),
     *     @OA\Parameter(name="permission_ids",in="query",required=false, @OA\Schema(type="string"),description="permission_ids"),
     *     @OA\Parameter(name="role_id",in="query",required=false, @OA\Schema(type="string"),description="role_id"),
     *     @OA\Parameter(name="role_ids",in="query",required=false, @OA\Schema(type="string"),description="role_ids"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="user_id"),
     *     @OA\Parameter(name="user_ids",in="query",required=false, @OA\Schema(type="string"),description="user_ids"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(NotificationStoreRequest $request): JsonResponse
    {
        $notification = $this->notificationService->store($request);
        return $notification ? ResponseHelper::responseSuccessStore(data: $notification) : ResponseHelper::responseFailedStore();
    }

}
