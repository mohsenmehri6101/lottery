<?php

namespace Modules\Authorization\Services;

use Exception;
use Illuminate\Support\Collection;
use \Illuminate\Database\Eloquent\Collection as CollectionEloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Services\UserService;
use Modules\Authorization\Entities\Role;
use Modules\Authorization\Http\Repositories\RoleRepository;
use Modules\Authorization\Http\Requests\Role\DeleteRoleToUserRequest;
use Modules\Authorization\Http\Requests\Role\RoleIndexRequest;
use Modules\Authorization\Http\Requests\Role\RoleShowRequest;
use Modules\Authorization\Http\Requests\Role\RoleStoreRequest;
use Modules\Authorization\Http\Requests\Role\RoleUpdateRequest;
use Modules\Authorization\Http\Requests\Role\SyncRoleToUserRequest;
use Symfony\Component\HttpFoundation\Response;

class RoleService
{
    public function __construct(public RoleRepository $roleRepository)
    {
    }

    public function index(RoleIndexRequest $request)
    {
        try {
            $fields = $request->validated();
            $withs = $fields['withs'] ?? [];

            $withs = is_string($withs) ? [$withs] : $withs;
            unset($fields['withs']);

            return $this->roleRepository->resolve_paginate(inputs: $fields, relations: $withs);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(RoleShowRequest $request, $role_id)
    {
        try {
            $fields = $request->validated();
            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->roleRepository->withRelations(relations: $withs)->findOrFail($role_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(RoleStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $name
             * @var $persian_name
             * @var $tag
             * @var $parent
             */
            extract($fields);

            $name = $name ?? null;
            $persian_name = $persian_name ?? null;
            $tag = $tag ?? null;
            $parent = $parent ?? null;

            $attributes = ['name' => $name ?? null];

            $values = [
                'name' => $name ?? null,
                'persian_name' => $persian_name ?? null,
                'tag' => $tag ?? null,
                'parent' => $parent ?? null,
            ];

            # save Role
            /** @var Role $role */
            $role = $this->roleRepository->firstOrCreate(attributes: $attributes, values: $values);

            DB::commit();
            return $this->roleRepository->find($role?->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(RoleUpdateRequest $request, $role_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $name
             * @var $persian_name
             * @var $tag
             * @var $parent
             */
            extract($fields);

            /** @var Role $role */
            $role = $this->roleRepository->findOrFail($role_id);

            # update role
            $fields_role = [
                'name' => $name ?? $role?->name ?? null,
                'persian_name' => $persian_name ?? $role?->persian_name ?? null,
                'tag' => $tag ?? $role?->tag ?? null,
                'parent' => $parent ?? $role?->parent ?? null,
            ];

            $this->roleRepository->update($role, $fields_role);

            DB::commit();
            return $this->roleRepository->find($role?->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($role_id): bool
    {
        DB::beginTransaction();
        try {
            # find Role
            /** @var Role $role */
            $role = $this->roleRepository->findOrFail($role_id);

            # delete role
            $this->roleRepository->delete($role);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncRoleToUser(SyncRoleToUserRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new SyncRoleToUserRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules(),
                    attributes: $loginRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $user_id
             * @var $detach
             * @var $role_id
             * @var $roles
             */
            extract($fields);

            $user_id = $user_id ?? null;
            $detach = $detach ?? true;
            $role_id = $role_id ?? null;
            $roles = $roles ?? [];

            if (isset($role_id) && $role_id) {
                $roles[] = $role_id;
                $roles = array_unique($roles);
            }

            # find user
            /** @var User $user */
            $user = User::query()->findOrFail($user_id);

            # sync role to user
            $user->roles()->sync($roles, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            # report($exception);
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteRoleToUser(DeleteRoleToUserRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $user_id
             * @var $touch
             * @var $role_id
             * @var $roles
             */
            extract($fields);

            $user_id = $user_id ?? null;
            $touch = $touch ?? true;
            $role_id = $role_id ?? null;
            $roles = $roles ?? [];

            if (isset($role_id) && $role_id) {
                $roles[] = $role_id;
                $roles = array_unique($roles);
            }

            # find user
            /** @var User $user */
            $user = User::query()->findOrFail($user_id);

            # detach role to user
            $user->roles()->detach($roles, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public static function prepare_roles(...$roles)
    {
        # prepare roles
        if (!is_array($roles) && (is_string($roles) || is_int($roles))) {
            $roles = [$roles];
        }

        if (is_array($roles)) {
            $roles = flatten($roles);
        }

        # standard roles
        $roles = (is_string($roles) || is_int($roles)) ? [$roles] : (is_array($roles) ? $roles : []);
        $roles = collect($roles);
        # convert all roles int  and validate
        $roles = $roles->map(function ($role) {
            $id = self::convertRoleToId($role);
            return $id;
        });
        # filter null and filled
        $roles = $roles->filter(function ($role) {
            return isset($role) && filled($role);
        });
        # unique
        $roles = $roles->unique();

        return $roles;
    }

    public static function convertRoleToId($role = null)
    {
        if (is_null($role)) {
            return $role;
        }

        if ($role instanceof Collection) {
            $role = $role->toArray() ?? [];
        }

        if ($role instanceof Role) {
            return $role?->id ?? null;
        } elseif (is_array($role) && isset($role['id']) && filled($role['id'])) {
            return $role['id'] ?? null;
        } elseif (is_numeric($role)) {
            return $role;
        }
        $role = $role && filled($role) ? Role::query()
            ->where('name', $role)
            /*->orWhere('persian_name', $role)*/
            ->orWhere('id', $role) : null;

        return ($role && $role->exists()) ? $role->first()?->id : $role;
    }

    public static function getRole($role = null, $name = null/*, $persian_name = null*/, $role_id = null, $throwException = false)
    {
        if (is_null($role) && is_null($name) /*&& is_null($persian_name)*/ && is_null($role_id)) {
            return null;
        }

        if ($role instanceof Role) {
            return $role;
        }

        $role_id = (isset($role_id) && filled($role_id)) ? $role_id : self::convertRoleToId($role);

        $role = Role::query()
            ->when($name, function ($query) use ($name) {
                return $query->orWhere('roles.name', $name);
            })
            /*->when($persian_name, function ($query) use ($persian_name) {
                return $query->orWhere('roles.persian_name', $persian_name);
            })*/
            ->when($role_id, function ($query) use ($role_id) {
                return $query->orWhere('roles.id', $role_id);
            });

        if ($role->exists()) {
            return $role->first() ?? null;
        } elseif ($throwException) {
            // todo should be set message
            throw new Exception(message: '', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function userHaveRoles($user, $roles = []): bool
    {
        $roles = self::prepare_roles($roles)?->toArray() ?? [];
        $roles_user = $user->roles->pluck('id')?->toArray() ?? [];
        $roles = collect($roles);
        $result = true;
        $roles->map(function ($role) use ($roles_user, &$result) {
            if (!in_array($role, $roles_user)) {
                $result = false;
            }
        });
        return $result;
    }

    public static function myRoles($user, $selects = ['id']): CollectionEloquent|array
    {
        try {
            # get user
            /** @var User|null $user */
            $user = UserService::getUser(user: $user);

            # get roles from user
            $roles_ids = $user?->roles()?->get()?->pluck('id')?->toArray() ?? [];
            return Role::query()->whereIn('id', $roles_ids)->select($selects)->get();
        } catch (Exception $exception) {
            # report($exception);
            DB::rollBack();
            throw $exception;
        }
    }

}
