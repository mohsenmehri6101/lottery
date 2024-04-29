<?php

namespace Modules\Authorization\Http\Controllers\Permission;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Authorization\Http\Requests\Permission\DeletePermissionToRoleRequest;
use Modules\Authorization\Http\Requests\Permission\DeletePermissionToUserRequest;
use Modules\Authorization\Http\Requests\Permission\GetPermissionsUserRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionIndexRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionShowRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionStoreRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionUpdateRequest;
use Modules\Authorization\Http\Requests\Permission\SyncPermissionToRoleRequest;
use Modules\Authorization\Http\Requests\Permission\SyncPermissionToUserRequest;
use Modules\Authorization\Services\PermissionService;

class PermissionController extends Controller
{
    public function __construct(public PermissionService $permissionService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authorization/permissions",
     *     tags={"authorization-permissions"},
     *     summary="لیست سطوح دسترسی",
     *     description="لیست تمام سطوح دسترسی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="string"),description="شناسه سطح دسترسی"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="نام سطح دسترسی"),
     *     @OA\Parameter(name="role_id",in="query",required=false, @OA\Schema(type="string"),description="آیدی نقش"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="نام فارسی سطح دسترسی"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="برچسب"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="string"),description="شناسه پدر"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:parentModel,children,roles"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function index(PermissionIndexRequest $request): JsonResponse
    {
        $permissions = $this->permissionService->index($request);
        return ResponseHelper::responseSuccess(data: $permissions);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authorization/permissions/{id}",
     *     tags={"authorization-permissions"},
     *     summary="نمایش سطح دسترسی",
     *     description="نمایش تکی سطح دسترسی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="شناسه سطح دسترسی"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:parentModel,children,roles"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function show(PermissionShowRequest $request,$permission_id): JsonResponse
    {
        $permission = $this->permissionService->show($request,$permission_id);
        return $permission ? ResponseHelper::responseSuccessShow(data: $permission) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authorization/permissions",
     *     tags={"authorization-permissions"},
     *     summary="ذخیره سطح دسترسی",
     *     description="ذخیره سطح دسترسی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=true, @OA\Schema(type="string"),description="نام انگلیسی سطح دسترسی"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="برچسب"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="string"),description="شناسه پدر"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="نام فارسی سطح دسترسی"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function store(PermissionStoreRequest $request)
    {
        $permission = $this->permissionService->store($request);
        return $permission ? ResponseHelper::responseSuccessStore(data: $permission) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/authorization/permissions/{id}",
     *     tags={"authorization-permissions"},
     *     summary="ویرایش سطح دسترسی",
     *     description="ویرایش سطح دسترسی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="شناسه سطح دسترسی"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="نام انگلیسی سطح دسترسی"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="برچسب"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="string"),description="شناسه پدر"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="نام فارسی سطح دسترسی"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function update(PermissionUpdateRequest $request, $permission_id)
    {
        $permission = $this->permissionService->update($request, $permission_id);
        return $permission ? ResponseHelper::responseSuccessUpdate(data:$permission) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authorization/permissions/sync-permission-to-user",
     *     tags={"authorization-permissions"},
     *     summary="اتصال سطح دسترسی(یا سطوح دسترسی) به کاربر",
     *     description="اتصال سطح دسترسی(یا سطوح دسترسی) به کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه کاربر"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="string"),description="detach فعلا بلااستفاده است در صورت نیاز فعال خواهد شد"),
     *     @OA\Parameter(name="permission_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه سطح دسترسی"),
     *     @OA\Parameter(name="permissions",in="query",required=false, @OA\Schema(type="string"),description="شناسه های سطح دسترسی"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function syncPermissionToUser(SyncPermissionToUserRequest $request)
    {
        if ($this->permissionService->syncPermissionToUser($request)) {
            return ResponseHelper::responseSuccess();
        }
        return ResponseHelper::responseError();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authorization/permissions/sync-permission-to-role",
     *     tags={"authorization-permissions"},
     *     summary="اتصال سطح دسترسی ( یا سطوح دسترسی) به نقش",
     *     description="اتصال سطح دسترسی(یا سطوح دسترسی) به نقش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="role_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه نقش(role)"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="string"),description="detach فعلا بلااستفاده است در صورت نیاز فعال خواهد شد"),
     *     @OA\Parameter(name="permission_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه سطح دسترسی"),
     *     @OA\Parameter(name="permissions",in="query",required=false, @OA\Schema(type="string"),description="شناسه های سطح دسترسی"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function syncPermissionToRole(SyncPermissionToRoleRequest $request)
    {
        if ($this->permissionService->syncPermissionToRole($request)) {
            return ResponseHelper::responseSuccess();
        }
        return ResponseHelper::responseError();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/authorization/permissions/delete-permission-to-user",
     *     tags={"authorization-permissions"},
     *     summary="حذف سطح دسترسی ( یا سطوح دسترسی) از کاربر",
     *     description="حذف سطح دسترسی(یا سطوح دسترسی) از کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه کاربر(user)"),
     *     @OA\Parameter(name="touch",in="query",required=false, @OA\Schema(type="string"),description="touch فعلا بلااستفاده است در صورت نیاز فعال خواهد شد"),
     *     @OA\Parameter(name="permission_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه سطح دسترسی"),
     *     @OA\Parameter(name="permissions",in="query",required=false, @OA\Schema(type="string"),description="شناسه های سطح دسترسی"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function deletePermissionToUser(DeletePermissionToUserRequest $request)
    {
        if ($this->permissionService->deletePermissionToUser($request)) {
            return ResponseHelper::responseSuccess();
        }
        return ResponseHelper::responseError();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/authorization/permissions/delete-permission-to-role",
     *     tags={"authorization-permissions"},
     *     summary="حذف سطح دسترسی ( یا سطوح دسترسی) از نقش",
     *     description="حذف سطح دسترسی(یا سطوح دسترسی) از نقش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="role_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه نقش(role)"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="string"),description="detach فعلا بلااستفاده است در صورت نیاز فعال خواهد شد"),
     *     @OA\Parameter(name="permission_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه سطح دسترسی"),
     *     @OA\Parameter(name="permissions",in="query",required=false, @OA\Schema(type="string"),description="شناسه های سطح دسترسی"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function deletePermissionToRole(DeletePermissionToRoleRequest $request)
    {
        if ($this->permissionService->deletePermissionToRole($request)) {
            return ResponseHelper::responseSuccess();
        }
        return ResponseHelper::responseError();
    }

}
