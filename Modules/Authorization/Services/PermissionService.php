<?php

namespace Modules\Authorization\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Http\Repositories\UserRepository;
use Modules\Authorization\Entities\Permission;
use Modules\Authorization\Entities\Role;
use Illuminate\Support\Facades\Validator;
use Modules\Authorization\Http\Repositories\PermissionRepository;
use Modules\Authorization\Http\Repositories\RoleRepository;
use Modules\Authorization\Http\Requests\Permission\DeletePermissionToRoleRequest;
use Modules\Authorization\Http\Requests\Permission\DeletePermissionToUserRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionIndexRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionShowRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionStoreRequest;
use Modules\Authorization\Http\Requests\Permission\PermissionUpdateRequest;
use Modules\Authorization\Http\Requests\Permission\SyncPermissionToRoleRequest;
use Modules\Authorization\Http\Requests\Permission\SyncPermissionToUserRequest;

class PermissionService
{
    public function __construct(public PermissionRepository $permissionRepository)
    {
    }

    public function index(PermissionIndexRequest $request)
    {
        $fields = $request->validated();
        $roleId = $fields["role_id"] ?? null;
        $relations = $fields['withs'] ?? [];
        unset($fields['withs']);
        if ($roleId) {
            $permissionsIds = Role::find($roleId)?->permissions->pluck("id")->toArray();

            #filter by role id
            $fields["id"] = $permissionsIds;
            unset($fields['role_id']);
        }
        return $this->permissionRepository->resolve_paginate(inputs: $fields, relations: $relations);
    }

    public function show(PermissionShowRequest $request, $permission_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->permissionRepository->withRelations(relations: $withs)->findOrFail($permission_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(PermissionStoreRequest|array $request)
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $userStoreRequest = new PermissionStoreRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            # $permission = $this->permissionRepository->create($fields);
            $permission = $this->permissionRepository->firstOrCreate
            (
                ['name' => $fields['name'] ?? null],
                $fields
            );

            DB::commit();
            return $permission;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(PermissionUpdateRequest $request, $permission_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Permission $permission */
            $permission = $this->permissionRepository->findOrFail($permission_id);

            $this->permissionRepository->update($permission, $fields);

            DB::commit();

            return $this->permissionRepository->findOrFail($permission_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncPermissionToUser(SyncPermissionToUserRequest $request): array
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $user_id
             * @var $permission_id
             * @var $permissions
             * @var $detach
             * */
            extract($fields);

            $user_id = $user_id ?? null;
            $permission_id = $permission_id ?? null;
            $permissions = $permissions ?? [];
            $detach = $detach ?? null;

            if (isset($permission_id)) {
                $permissions[] = $permission_id;
            }

            /** @var User $user */
            $user = User::query()->findOrFail($user_id);
            $status = $user->permissions()->sync($permissions, $detach);

            DB::commit();
            return $status;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncPermissionToRole(SyncPermissionToRoleRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $userStoreRequest = new SyncPermissionToRoleRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            $permissions = $fields['permissions'] ?? [];
            $detach = $fields['detach'] ?? true;
            $role_id = $fields['role_id'] ?? [];

            if (isset($fields['permission_id'])) {
                array_push($permissions, $fields['permission_id']);
            }
            /** @var Role $role */
            $role = Role::query()->findOrFail($role_id);
            $status = $role->permissions()->sync($permissions, $detach);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            # report($exception);
            DB::rollBack();
            throw $exception;
        }
    }

    public function deletePermissionToUser(DeletePermissionToUserRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $user_id
             * @var $permission_id
             * @var $permissions
             * @var $touch
             * */
            extract($fields);


            $user_id = $user_id ?? null;
            $permission_id = $permission_id ?? null;
            $permissions = $permissions ?? [];
            $touch = $touch ?? true;

            if (isset($fields['permission_id'])) {
                $permissions[] = $fields['permission_id'];
                $permissions = array_unique($permissions);
            }

            /** @var UserRepository $userRepository */
            $userRepository = resolve('UserRepository');
            /** @var User $user */
            $user = $userRepository->findOrFail($user_id);

            $status = $user->permissions()->detach($permissions/*,$touch*/);
            DB::commit();
            return true;
        } catch (Exception $exception) {
            # report($exception);
            DB::rollBack();
            throw $exception;
        }
    }

    public function deletePermissionToRole(DeletePermissionToRoleRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $role_id
             * @var $permission_id
             * @var $permissions
             * @var $touch
             * */
            extract($fields);

            $role_id = $role_id ?? null;
            $permission_id = $permission_id ?? null;
            $permissions = $permissions ?? [];
            $touch = $touch ?? true;

            if (isset($permission_id)) {
                $permissions[] = $permission_id;
            }

            /** @var RoleRepository $roleRepository */
            $roleRepository = resolve('RoleRepository');

            /** @var Role $role */
            $role = $roleRepository->findOrFail($role_id);

            $role->permissions()->detach($permissions/*,$touch*/);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            # report($exception);
            DB::rollBack();
            throw $exception;
        }
    }

    public static function getPermission($permission = null, $name = null, $persian_name = null, $permission_id = null)
    {
        // todo not work completely true;
        if (is_null($permission) && is_null($name) && is_null($persian_name) && is_null($permission_id)) {
            return null;
        }

        if ($permission instanceof Permission) {
            return $permission;
        }

        $permission_id = (isset($permission_id) && filled($permission_id)) ? $permission_id : self::convertPermissionToId($permission);

        $permission = Permission::query()
            ->when($name, function ($query) use ($name) {
                return $query->orWhere('permissions.name', $name);
            })
            ->when($persian_name, function ($query) use ($persian_name) {
                return $query->orWhere('permissions.persian_name', $persian_name);
            })
            ->when($permission_id, function ($query) use ($permission_id) {
                return $query->orWhere('permissions.id', $permission_id);
            });
        return $permission->exists() ? $permission->first() : null;
    }

    public static function convertPermissionToId($permission = null)
    {
        if ($permission instanceof Permission) {
            return $permission?->id ?? null;
        } elseif (is_array($permission) && isset($permission['id']) && filled($permission['id'])) {
            return $permission['id'] ?? null;
        }

        $permission = $permission && filled($permission) ?
            Permission::query()
                ->where('id', $permission)
                ->orWhere('name', $permission)
                ->orWhere('persian_name', $permission) : null;

        return ($permission && $permission?->exists()) ? $permission->first()?->id : null;
    }

    public static function prepare_permissions(...$permissions): \Illuminate\Support\Collection
    {
        # prepare permissions
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $permissions = call_user_func_array('array_merge', $permissions);
        # standard permissions
        $permissions = (is_string($permissions) || is_int($permissions)) ? [$permissions] : (is_array($permissions) ? $permissions : []);
        $permissions = collect($permissions);
        # convert all permissions int  and validate
        $permissions = $permissions->map(function ($permission) {
            return self::convertPermissionToId($permission);
        });
        # filter null and filled
        $permissions = $permissions->filter(function ($permission) {
            return isset($permission) && filled($permission);
        });
        # unique
        $permissions = $permissions->unique();

        return $permissions;
    }

    public static function user_have_permission($permission, $user = null): bool
    {
        if (is_null($permission)) {
            return false;
        }

        /** @var User $user */
        $user = $user ?? auth()?->user() ?? null;

        /** @var Permission|null $permission_object */
        $permission_object = PermissionService::getPermission($permission);

        return ($user && $user->hasPermissionTo($permission_object?->id)) || self::user_have_permission(permission: $permission_object?->parentModel?->id ?? null, user: $user);

    }

}
