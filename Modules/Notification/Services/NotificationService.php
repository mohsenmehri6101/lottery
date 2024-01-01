<?php

namespace Modules\Notification\Services;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Http\Repositories\NotificationRepository;
use Modules\Notification\Http\Requests\Notification\NotificationStoreRequest;

class NotificationService extends Controller
{
    public function __construct(public NotificationRepository $notificationRepository)
    {
    }

    public function store(NotificationStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $title
             * @var $text
             * @var $send_at
             * @var $priority
             * @var $permission_id
             * @var $permission_ids
             * @var $role_id
             * @var $role_ids
             * @var $user_id
             * @var $user_ids
             */
            extract($fields);

            $permission_id = $permission_id ?? null;
            $permission_ids = $permission_ids ?? [];
            if (isset($permission_id)) {
                $permission_ids[] = $permission_id;
            }

            $role_id = $role_id ?? null;
            $role_ids = $role_ids ?? [];
            if (isset($role_id)) {
                $role_ids[] = $role_id;
            }

            $user_id = $user_id ?? null;
            $user_ids = $user_ids ?? [];
            if (isset($user_id)) {
                $user_ids[] = $user_id;
            }

            $notification = $this->notificationRepository->create($fields);

            DB::commit();
            return $notification;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
