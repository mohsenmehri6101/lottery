<?php

namespace Modules\Authorization\Http\Controllers\Role;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Authorization\Http\Requests\Role\DeleteRoleToUserRequest;
use Modules\Authorization\Http\Requests\Role\RoleIndexRequest;
use Modules\Authorization\Http\Requests\Role\RoleShowRequest;
use Modules\Authorization\Http\Requests\Role\RoleStoreRequest;
use Modules\Authorization\Http\Requests\Role\RoleUpdateRequest;
use Modules\Authorization\Http\Requests\Role\SyncRoleToUserRequest;
use Modules\Authorization\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(public RoleService $roleService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authorization/roles",
     *     tags={"authorization-roles"},
     *     summary="لیست نقش ها",
     *     description="لیست تمام نقش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="string"),description="شناسه نقش"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="نام نقش"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="نام فارسی نقش"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="برچسب"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs availables:permissions"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function index(RoleIndexRequest $request): JsonResponse
    {
        $roles = $this->roleService->index($request);
        $data = ['data' => $roles];
        return ResponseHelper::responseSuccess(data: $data);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authorization/roles/{id}",
     *     tags={"authorization-roles"},
     *     summary="نمایش نقش",
     *     description="نمایش نقش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=false, @OA\Schema(type="string"),description="شناسه نقش"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs availables:permissions"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function show(RoleShowRequest $request, $role_id): JsonResponse
    {
        $role = $this->roleService->show($request, $role_id);
        return $role ? ResponseHelper::responseSuccessShow(data: $role) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authorization/roles",
     *     tags={"authorization-roles"},
     *     summary="ذخیره نقش",
     *     description="ذخیره نقش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=true, @OA\Schema(type="string"),description="نام انگلیسی نقش"),
     *     @OA\Parameter(name="persian_name",in="query",required=true, @OA\Schema(type="string"),description="نام فارسی نقش"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="برچسب"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function store(RoleStoreRequest $request)
    {
        $role = $this->roleService->store($request);
        return $role ? ResponseHelper::responseSuccessStore(data: $role) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/authorization/roles/{id}",
     *     tags={"authorization-roles"},
     *     summary="ویرایش نقش",
     *     description="ویرایش نقش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=false, @OA\Schema(type="string"),description="شناسه نقش"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="نام انگلیسی نقش"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="نام فارسی نقش"),
     *     @OA\Parameter(name="tag",in="query",required=false, @OA\Schema(type="string"),description="برچسب"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function update(RoleUpdateRequest $request, $role_id)
    {
        $status_update = $this->roleService->update($request, $role_id);
        return $status_update ? ResponseHelper::responseSuccessUpdate() : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/authorization/roles/{id}",
     *     tags={"authorization-roles"},
     *     summary="حذف نقش",
     *     description="حذف نقش",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=false, @OA\Schema(type="string"),description="شناسه نقش"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function destroy($role_id)
    {
        $status_delete = $this->roleService->destroy($role_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Post  (
     *     path="/api/v1/authorization/roles/sync-role-to-user",
     *     tags={"authorization-roles"},
     *     summary="اتصال نقش(یا نقش ها) به کاربر",
     *     description="اتصال نقش(یا نقش ها) به کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه کاربر"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="string"),description="detach"),
     *     @OA\Parameter(name="role_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه نقش"),
     *     @OA\Parameter(name="roles[]",in="query",required=false, @OA\Schema(type="array",@OA\Items(type="string")),description="لیست نقش ها"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function syncRoleToUser(SyncRoleToUserRequest $request)
    {
        $this->roleService->syncRoleToUser($request);
        return ResponseHelper::responseSuccess();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/authorization/roles/delete-role-to-user",
     *     tags={"authorization-roles"},
     *     summary="حذف نقش(یا نقش ها) از کاربر",
     *     description="حذف نقش(یا نقش ها) از کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه کاربر"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="string"),description="detach"),
     *     @OA\Parameter(name="role_id",in="query",required=false, @OA\Schema(type="string"),description="شناسه نقش"),
     *     @OA\Parameter(name="roles[]",in="query",required=false, @OA\Schema(type="array",@OA\Items(type="string")),description="لیست نقش ها"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function deleteRoleToUser(DeleteRoleToUserRequest $request)
    {
        $status_delete = $this->roleService->deleteRoleToUser($request);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
