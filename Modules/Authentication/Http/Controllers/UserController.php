<?php

namespace Modules\Authentication\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Authentication\Http\Requests\User\CheckProfileRequest;
use Modules\Authentication\Http\Requests\User\NewUserRequest;
use Modules\Authentication\Http\Requests\User\UpdateAvatarRequest;
use Modules\Authentication\Http\Requests\User\UpdateProfileRequest;
use Modules\Authentication\Http\Requests\User\UserIndexRequest;
use Modules\Authentication\Http\Requests\User\UserShowRequest;
use Modules\Authentication\Http\Requests\User\UserStoreRequest;
use Modules\Authentication\Http\Requests\User\UserUpdateRequest;
use Modules\Authentication\Services\UserService;

class UserController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authentication/users",
     *     tags={"users"},
     *     summary="لیست کاربران",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false,@OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false,@OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false,@OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="search",in="query",required=false, @OA\Schema(type="string"),description="search"),
     *     @OA\Parameter(name="username",in="query",required=false, @OA\Schema(type="string"),description="username"),
     *     @OA\Parameter(name="password",in="query",required=false, @OA\Schema(type="string"),description="password"),
     *     @OA\Parameter(name="email",in="query",required=false, @OA\Schema(type="string"),description="email"),
     *     @OA\Parameter(name="mobile",in="query",required=false, @OA\Schema(type="string"),description="mobile"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:userCreator,userEditor,gyms,userDetail,events,notifications,readNotifications,unreadNotifications,roles,permissions"),
     *     @OA\Parameter(name="role_ids", in="query", required=false, @OA\Schema(type="array", @OA\Items(type="integer")), description="Array of role IDs"),
     *     @OA\Parameter(name="order_by",in="query",required=false,@OA\Schema(type="string"),description="Column to sort by"),
     *     @OA\Parameter(name="direction_by",in="query",required=false,@OA\Schema(type="string", enum={"asc", "desc"}),description="Sort direction (asc or desc)"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(UserIndexRequest $request): JsonResponse
    {
        $users = $this->userService->index($request);
        return ResponseHelper::responseSuccess(data: $users);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authentication/users/{id}",
     *     tags={"users"},
     *     summary="نمایش کاربر(تکی)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(UserShowRequest $request, $user_id): JsonResponse
    {
        $user = $this->userService->show($request, $user_id);
        return $user ? ResponseHelper::responseSuccessShow(data: $user) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authentication/users",
     *     tags={"users"},
     *     summary="ذخیره کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="parent_code",in="query",required=false, @OA\Schema(type="string"),description="parent_code"),
     *     @OA\Parameter(name="username",in="query",required=false, @OA\Schema(type="string"),description="username"),
     *     @OA\Parameter(name="password",in="query",required=false, @OA\Schema(type="string"),description="password"),
     *     @OA\Parameter(name="email",in="query",required=false, @OA\Schema(type="string"),description="email"),
     *     @OA\Parameter(name="mobile",in="query",required=false, @OA\Schema(type="string"),description="mobile"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(required={"file"},
     *                  @OA\Property(property="avatar",type="file", format="binary", description="avatar"),
     *              )
     *          )
     *      ),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="family",in="query",required=false, @OA\Schema(type="string"),description="family"),
     *     @OA\Parameter(name="father",in="query",required=false, @OA\Schema(type="string"),description="father"),
     *     @OA\Parameter(name="national_code",in="query",required=false, @OA\Schema(type="string"),description="national_code"),
     *     @OA\Parameter(name="birthday",in="query",required=false, @OA\Schema(type="string"),description="birthday"),
     *     @OA\Parameter(name="gender",in="query",required=false, @OA\Schema(type="string"),description="gender"),
     *     @OA\Parameter(name="address",in="query",required=false, @OA\Schema(type="string"),description="address"),
     *     @OA\Parameter(name="role_ids", in="query", required=false, @OA\Schema(type="array", @OA\Items(type="integer")), description="Array of role IDs"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = $this->userService->store($request);
        return $user ? ResponseHelper::responseSuccessStore(data: $user) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authentication/users/new-user",
     *     tags={"users"},
     *     summary="ذخیره کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="parent_code",in="query",required=false, @OA\Schema(type="string"),description="parent_code"),
     *     @OA\Parameter(name="username",in="query",required=false, @OA\Schema(type="string"),description="username"),
     *     @OA\Parameter(name="password",in="query",required=false, @OA\Schema(type="string"),description="password"),
     *     @OA\Parameter(name="email",in="query",required=false, @OA\Schema(type="string"),description="email"),
     *     @OA\Parameter(name="mobile",in="query",required=false, @OA\Schema(type="string"),description="mobile"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(required={"file"},
     *                  @OA\Property(property="avatar",type="file", format="binary", description="avatar"),
     *              )
     *          )
     *      ),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="family",in="query",required=false, @OA\Schema(type="string"),description="family"),
     *     @OA\Parameter(name="father",in="query",required=false, @OA\Schema(type="string"),description="father"),
     *     @OA\Parameter(name="national_code",in="query",required=false, @OA\Schema(type="string"),description="national_code"),
     *     @OA\Parameter(name="birthday",in="query",required=false, @OA\Schema(type="string"),description="birthday"),
     *     @OA\Parameter(name="gender",in="query",required=false, @OA\Schema(type="string"),description="gender"),
     *     @OA\Parameter(name="address",in="query",required=false, @OA\Schema(type="string"),description="address"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function newUser(NewUserRequest $request): JsonResponse
    {
        $user = $this->userService->newUser($request);
        // todo should be delete this function.
        return $user ? ResponseHelper::responseSuccessStore(data: $user) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/authentication/users/{id}",
     *     tags={"users"},
     *     summary="ویرایش کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *     @OA\Parameter(name="username",in="query",required=false, @OA\Schema(type="string"),description="username"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(required={},
     *                @OA\Property(property="avatar",type="string", format="binary", description="avatar"),
     *              )
     *          )
     *      ),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="family",in="query",required=false, @OA\Schema(type="string"),description="family"),
     *     @OA\Parameter(name="father",in="query",required=false, @OA\Schema(type="string"),description="father"),
     *     @OA\Parameter(name="national_code",in="query",required=false, @OA\Schema(type="string"),description="national_code"),
     *     @OA\Parameter(name="birthday",in="query",required=false, @OA\Schema(type="string"),description="birthday"),
     *     @OA\Parameter(name="gender",in="query",required=false, @OA\Schema(type="string"),description="gender"),
     *     @OA\Parameter(name="address",in="query",required=false, @OA\Schema(type="string"),description="address"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(UserUpdateRequest $request, $user_id): JsonResponse
    {
        $user = $this->userService->update($request, $user_id);
        return $user ? ResponseHelper::responseSuccessUpdate(data: $user) : ResponseHelper::responseFailedUpdate();
    }


    /**
     * @OA\Put (
     *     path="/api/v1/authentication/users/update-profile",
     *     tags={"users"},
     *     summary="update profile user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="username",in="query",required=false, @OA\Schema(type="string"),description="username"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="family",in="query",required=false, @OA\Schema(type="string"),description="family"),
     *     @OA\Parameter(name="father",in="query",required=false, @OA\Schema(type="string"),description="father"),
     *     @OA\Parameter(name="national_code",in="query",required=false, @OA\Schema(type="string"),description="national_code"),
     *     @OA\Parameter(name="birthday",in="query",required=false, @OA\Schema(type="string"),description="birthday"),
     *     @OA\Parameter(name="gender",in="query",required=false, @OA\Schema(type="string"),description="gender"),
     *     @OA\Parameter(name="address",in="query",required=false, @OA\Schema(type="string"),description="address"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->userService->updateProfile($request);
        return ResponseHelper::responseSuccessUpdate(data: $user);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authentication/users/update-avatar",
     *     tags={"users"},
     *     summary="update avatar user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="avatar",
     *                     description="Avatar file",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function updateAvatar(UpdateAvatarRequest $request): JsonResponse
    {
        return $this->userService->updateAvatar($request) ? ResponseHelper::responseSuccessUpdate() : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/authentication/users/{id}",
     *     tags={"users"},
     *     summary="حذف کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($user_id): JsonResponse
    {
        $status_delete = $this->userService->destroy($user_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/authentication/users/delete-avatar/{id}",
     *     tags={"users"},
     *     summary="حذف آواتار کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteAvatar($user_id): JsonResponse
    {
        $status_delete = $this->userService->deleteAvatar($user_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authentication/users/list-status-gender",
     *     tags={"users"},
     *     summary="لیست جنسیت",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function listStatusGender(): JsonResponse
    {
        $status_users = $this->userService->listStatusGender();
        return ResponseHelper::responseSuccess(data: $status_users);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authentication/users/list-status-user",
     *     tags={"users"},
     *     summary="لیست وضعیت کاربر",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function listStatusUser(): JsonResponse
    {
        $status_users = $this->userService->listStatusUser();
        $status_users = collect($status_users)->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        });
        return ResponseHelper::responseSuccess(data: $status_users);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/authentication/users/check-profile",
     *     tags={"users"},
     *     summary="Check user profile completion percentage",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="user_id", in="query", required=false, description="User ID to check profile", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function checkProfile(CheckProfileRequest $request): JsonResponse
    {
        $response = $this->userService->checkProfile($request);
        return ResponseHelper::responseSuccess(data: $response);
    }

}
