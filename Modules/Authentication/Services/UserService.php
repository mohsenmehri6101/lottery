<?php

namespace Modules\Authentication\Services;

use App\Permissions\RolesEnum;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Entities\User;
use Illuminate\Support\Facades\Validator;
use Modules\Authentication\Entities\UserDetail;
use Modules\Authentication\Http\Repositories\UserDetailRepository;
use Modules\Authentication\Http\Repositories\UserRepository;
use Modules\Authentication\Http\Requests\User\CheckProfileRequest;
use Modules\Authentication\Http\Requests\User\NewUserRequest;
use Modules\Authentication\Http\Requests\User\UpdateAvatarRequest;
use Modules\Authentication\Http\Requests\User\UpdateProfileRequest;
use Modules\Authentication\Http\Requests\User\UserIndexRequest;
use Modules\Authentication\Http\Requests\User\UserShowRequest;
use Modules\Authentication\Http\Requests\User\UserStoreRequest;
use Modules\Authentication\Http\Requests\User\UserUpdateRequest;
use Modules\Authorization\Entities\Role;
use Modules\Gym\Services\ImageService;

class UserService
{
    public function __construct(public UserRepository $userRepository, public UserDetailRepository $userDetailRepository)
    {
    }
    public function index(UserIndexRequest $request)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $paginate
             * @var $per_page
             * @var $search
             * @var $id
             * @var $code
             * @var $parent_code
             * @var $username
             * @var $password
             * @var $email
             * @var $mobile
             * @var $status
             * @var $avatar
             * @var $mobile_verified_at
             * @var $email_verified_at
             * @var $user_creator
             * @var $user_editor
             * @var $created_at
             * @var $updated_at
             * @var $deleted_at
             * @var $user_id
             * @var $name
             * @var $family
             * @var $father
             * @var $national_code
             * @var $birthday
             * @var $gender
             * @var $address
             * @var $users_details_user_creator
             * @var $users_details_user_editor
             * @var $users_details_created_at
             * @var $users_details_updated_at
             * @var $users_details_deleted_at
             * @var $role_ids
             * @var $withs
             * @var $order_by
             * @var $direction_by
             */
            extract($fields);

            $role_ids = $role_ids ?? [];

            $withs = $withs ?? [];

            if (count($role_ids) > 0) {
                $withs[] = 'roles';
                $withs = array_unique($withs);
            }

            $fields_users_table = [
                'search' => $search ?? null,
                'id' => $id ?? null,
                'code' => $code ?? null,
                'parent_code' => $parent_code ?? null,
                'username' => $username ?? null,
                'password' => $password ?? null,
                'email' => $email ?? null,
                'mobile' => $mobile ?? null,
                'status' => $status ?? null,
                'avatar' => $avatar ?? null,
                'mobile_verified_at' => $mobile_verified_at ?? null,
                'email_verified_at' => $email_verified_at ?? null,
                'user_creator' => $user_creator ?? null,
                'user_editor' => $user_editor ?? null,
                'created_at' => $created_at ?? null,
                'updated_at' => $updated_at ?? null,
                'deleted_at' => $deleted_at ?? null
            ];
            $fields_users_table = array_filter($fields_users_table);

            $fields_user_details_table = [
                'search' => $search ?? null,
                'user_id' => $user_id ?? null,
                'name' => $name ?? null,
                'family' => $family ?? null,
                'father' => $father ?? null,
                'national_code' => $national_code ?? null,
                'birthday' => $birthday ?? null,
                'gender' => $gender ?? null,
                'address' => $address ?? null,
                'user_creator' => $users_details_user_creator ?? null,
                'user_editor' => $users_details_user_editor ?? null,
                'created_at' => $users_details_created_at ?? null,
                'updated_at' => $users_details_updated_at ?? null,
                'deleted_at' => $users_details_deleted_at ?? null
            ];

            $fields_user_details_table = array_filter($fields_user_details_table);

            $query = $this->userRepository->queryFull(
                inputs: $fields_users_table,
                relations: $withs,
                orderByColumn: $order_by ?? 'id',
                directionOrderBy: $direction_by ?? 'desc',
            );

            $query = $query->when(isset($withs['userDetail']) && filled($withs['userDetail']), function (Builder $user_query) use ($withs, $fields_user_details_table) {
                return $user_query->whereHas('userDetail', function (Builder $query) use ($withs, $fields_user_details_table) {
                    return $query->when(count($fields_user_details_table) > 0, function ($queryUserDetail) use ($fields_user_details_table) {
                        /** @var UserDetailRepository $userDetailRepository */
                        $userDetailRepository = resolve('UserDetailRepository');
                        return $userDetailRepository->queryByInputs(query: $queryUserDetail, inputs: $fields_user_details_table);
                    });
                });
            });

            if (count($role_ids) > 0) {
                $query = $query->when(in_array('roles',$withs),function(Builder $query_role) use ($role_ids) {
                    return $query_role->whereHas('roles', function ($queryRoles) use ($role_ids) {
                        $queryRoles->whereIn('id', $role_ids);
                    });
                });
            }

            return $this->userRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function show(UserShowRequest $request, $user_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->userRepository->withRelations(relations: $withs)->findOrFail($user_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function store(UserStoreRequest|array $request)
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $userStoreRequest = new UserStoreRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $parent_code
             * @var $username
             * @var $password
             * @var $email
             * @var $mobile
             * @var $status
             * @var $avatar
             * @var $name
             * @var $family
             * @var $father
             * @var $national_code
             * @var $birthday
             * @var $gender
             * @var $address
             * @var $role_ids
             */
            extract($fields);

            if (!isset($mobile) || !filled($mobile)) {
                throw new Exception('ورود تلفن همراه برای ثبت نام ضروری است');
            }

            # user
            $user_fields = [
                'parent_code' => $parent_code ?? null,
                'username' => $username ?? null,
                'password' => $password ?? $username ?? $mobile ?? $email ?? null/* todo random password */,
                'email' => $email ?? null,
                'mobile' => $mobile ?? null,
                'status' => $status ?? null,
                // 'avatar' => $avatar ?? null,
            ];
            $user_fields = array_filter($user_fields);

            $user_details_fields = [
                'name' => $name ?? null,
                'family' => $family ?? null,
                'father' => $father ?? null,
                'national_code' => $national_code ?? null,
                'birthday' => $birthday ?? null,
                'gender' => $gender ?? null,
                'address' => $address ?? null,
            ];
            $user_details_fields = array_filter($user_details_fields);

            # save avatar
            $avatar = $avatar ?? null;
            if ($avatar) {
                # delete avatar before
                $name_file = ImageService::setNameFile($avatar);
                // todo convert image change image size and with and heigh
                $path_avatar = $avatar->storeAs('avatars', $name_file);
                if ($path_avatar) {
                    $user_fields['avatar'] = $path_avatar;
                }
            }

            # save user
            /** @var User $user */
            $user = $this->userRepository->create($user_fields);

            # save user_details
            /** @var UserDetail $user */
            $user->userDetail()->create($user_details_fields);

            $role_default = Role::query()->where('name', RolesEnum::user->name)->first()->id;
            $role_ids = $role_ids ?? [$role_default];

            $user->roles()->sync($role_ids);

            DB::commit();
            return $this->userRepository->withRelations(relations: ['userDetail'])->findOrFail($user?->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function newUser(NewUserRequest|array $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $parent_code
             * @var $username
             * @var $password
             * @var $email
             * @var $mobile
             * @var $status
             * @var $avatar
             * @var $name
             * @var $family
             * @var $father
             * @var $national_code
             * @var $birthday
             * @var $gender
             * @var $address
             */
            extract($fields);

            # user
            $user_fields = [
                'parent_code' => $parent_code ?? null,
                'username' => $username ?? null,
                'password' => $password ?? $username ?? $mobile ?? $email /* todo random password */,
                'email' => $email ?? null,
                'mobile' => $mobile ?? null,
                'status' => $status ?? null,
            ];
            $user_fields = array_filter($user_fields);

            $user_details_fields = [
                'name' => $name ?? null,
                'family' => $family ?? null,
                'father' => $father ?? null,
                'national_code' => $national_code ?? null,
                'birthday' => $birthday ?? null,
                'gender' => $gender ?? null,
                'address' => $address ?? null,
            ];
            $user_details_fields = array_filter($user_details_fields);

            # save avatar
            $avatar = $avatar ?? null;
            if ($avatar) {
                # delete avatar before
                $name_file = ImageService::setNameFile($avatar);
                // todo convert image change image size and with and heigh
                $path_avatar = $avatar->storeAs('avatars', $name_file);
                if ($path_avatar) {
                    $user_fields['avatar'] = $path_avatar;
                }
            }

            # save user
            /** @var User $user */
            $user = $this->userRepository->create($user_fields);

            $user->userDetail()->create($user_details_fields);

            $role_default = Role::query()->where('name', RolesEnum::user->name)->first()->id;
            $role_ids = [$role_default];

            $user->roles()->sync($role_ids);

            DB::commit();
            return $this->userRepository->withRelations(relations: ['userDetail'])->findOrFail($user?->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function update(UserUpdateRequest|array $request, $user_id)
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $userStoreRequest = new UserStoreRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $parent_code
             * @var $username
             * @var $password
             * @var $email
             * @var $mobile
             * @var $status
             * @var $avatar
             * @var $name
             * @var $family
             * @var $father
             * @var $national_code
             * @var $birthday
             * @var $gender
             * @var $address
             */
            extract($fields);

            # user
            $user_fields = [
                'username' => $username ?? null,
                'status' => $status ?? null,
            ];
            $user_fields = array_filter($user_fields);

            $user_details_fields = [
                'name' => $name ?? null,
                'family' => $family ?? null,
                'father' => $father ?? null,
                'national_code' => $national_code ?? null,
                'birthday' => $birthday ?? null,
                'gender' => $gender ?? null,
                'address' => $address ?? null,
            ];
            $user_details_fields = array_filter($user_details_fields);

            # find user
            /** @var User $user */
            $user = $this->userRepository->findOrFail($user_id);
            /** @var UserDetail $userDetail */
            $userDetail = $user->userDetail();

            # update avatar
            $avatar = $avatar ?? null;
            if ($avatar) {
                # delete avatar before
                $name_file = ImageService::setNameFile($avatar);
                // todo convert image change image size and with and heigh
                $path_avatar = $avatar->storeAs('avatars', $name_file);
                if ($path_avatar) {
                    helperDeleteFiles($user->avatar);
                    $user_fields['avatar'] = $path_avatar;
                }
            }

            # update table users
            if (count($user_fields) > 0) {
                $this->userRepository->update($user, $user_fields);
            }

            # update table user_details
            if (count($user_details_fields) > 0) {
                $userDetail->update($user_details_fields);
            }

            # $user->save();
            # $userDetail->save();

            DB::commit();
            return $this->userRepository->withRelations(relations: ['userDetail'])->findOrFail($user?->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function destroy($user_id): bool
    {
        DB::beginTransaction();
        try {
            # find User
            /** @var User $user */
            $user = $this->userRepository->findOrFail($user_id);

            # delete userDetail
            $user->userDetail()->delete();

            # delete user
            $this->userRepository->delete($user);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function deleteAvatar($user_id): bool
    {
        DB::beginTransaction();
        try {
            # find User
            /** @var User $user */
            $user = $this->userRepository->findOrFail($user_id);

            if ($user && helperDeleteFiles($user->avatar)) {
                $user->avatar = null;
                $user->save();
            }

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function checkProfile(CheckProfileRequest $request): array
    {
        $fields = $request->validated();
        $user_id = $fields['user_id'] ?? null;
        /*  ---------------------  */
        /** @var User $user */
        $user = null;
        if (filled($user_id) && user_have_role(['super_admin', 'admin'])) {
            $user = User::query()->find($user_id);
        } else {
            $user = auth()->user();
        }

        /*  ---------------------  */
        $userModel = User::query()->find($user->id);
        $userDetailModel = UserDetail::query()->where('user_id', $user->id)->first();
        /*  ---------------------  */
        $userColumns = $userModel->getFillable();
        $userDetailColumns = $userDetailModel->getFillable();
        /*  ---------------------  */

        $userArray = $userModel->toArray();
        $userDetailArray = $userDetailModel->toArray();
        /*  ------------------------------------------  */

        $columnWeights = [
            'email' => 1,
            'mobile' => 1,
            'name' => 1,
            'avatar' => 1,
            'family' => 1,
            'father' => 1,
            'national_code' => 1,
            'birthday' => 1,
            'gender' => 1,
            'city_id' => 1,
            'address' => 1,
        ];

        $totalColumns = count($columnWeights);
        $nullColumns = [];

        foreach ($columnWeights as $column => $weight) {
            if (in_array($column, $userColumns)) {
                if (!filled($userArray[$column])) {
                    $nullColumns[] = $column;
                }
            } elseif (in_array($column, $userDetailColumns)) {
                if (!filled($userDetailArray[$column])) {
                    $nullColumns[] = $column;
                }
            }
        }

        $totalWeight = 0;
        foreach ($nullColumns as $column) {
            if (array_key_exists($column, $columnWeights)) {
                $totalWeight += $columnWeights[$column];
            }
        }

        # it's not that simple
        $totalWeightPossible = array_sum($columnWeights);
        $completionPercentage = 100 - (($totalWeight / $totalWeightPossible) * 100);
        $completionPercentage = max(0, $completionPercentage);
        $completionPercentage = (int)$completionPercentage;

        // Prepare the response
        return [
            'completion_percentage' => $completionPercentage,
            'null_columns' => $nullColumns,
        ];
    }
    public function updateProfile(UpdateProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $userStoreRequest = new UserStoreRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $username
             * @var $name
             * @var $family
             * @var $father
             * @var $national_code
             * @var $birthday
             * @var $gender
             * @var $address
             */
            extract($fields);

            # user
            $user_fields = [
                'username' => $username ?? null,
            ];
            $user_fields = array_filter($user_fields);

            $user_details_fields = [
                'name' => $name ?? null,
                'family' => $family ?? null,
                'father' => $father ?? null,
                'national_code' => $national_code ?? null,
                'birthday' => $birthday ?? null,
                'gender' => $gender ?? null,
                'address' => $address ?? null,
            ];
            $user_details_fields = array_filter($user_details_fields);

            # find user
            /** @var User $user */
            $user = get_user_login();

            # update table users
            $this->userRepository->update($user, $user_fields);

            # update table user_detail
            $user->userDetail()->update($user_details_fields);

            DB::commit();
            return $this->userRepository->withRelations(relations: ['userDetail'])->findOrFail($user?->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public static function saveAvatar($avatar, User $user): void
    {
        $avatar = $avatar ?? null;
        if ($avatar) {
            $name_file = ImageService::setNameFile($avatar);
            $path_avatar = $avatar->storeAs('avatars', $name_file);
            if ($path_avatar) {
                $user->avatar = $path_avatar;
            }
            $user->update();
        }
    }
    public function updateAvatar(UpdateAvatarRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $avatar
             */
            extract($fields);

            /** @var User $user */
            $user = auth()->user();

            self::saveAvatar($avatar, $user);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function listStatusUser(): array|bool|int|string|null
    {
        return User::getStatusUserTitle();
    }
    public function listStatusGender(): array|bool|int|string|null
    {
        return UserDetail::getStatusGenderTitle();
    }
    public static function convertUserToId($user = null, $default = null)
    {
        if (is_null($user)) {
            return $user;
        }

        if ($user instanceof \Illuminate\Support\Collection) {
            $user = $user->toArray();
        }

        if ($user instanceof User) {
            return $user?->id ?? null;
        } elseif (is_array($user) && isset($user['id']) && filled($user['id'])) {
            return $user['id'] ?? null;
        }

        $user = $user && filled($user) ?
            User::query()
                ->where('mobile', $user)
                ->orWhere('username', $user)
                ->orWhere('email', $user)
                ->orWhere('code', $user)
                ->orWhere('id', $user) : null;

        return ($user && $user?->exists()) ? $user->first()?->id : $default;
    }
    public static function getUser($user = null, $email = null, $mobile = null, $username = null, $user_id = null, $withs = [])
    {
        if (is_null($user) && is_null($email) && is_null($mobile) && is_null($username) && is_null($user_id)) {
            return null;
        }

        if ($user instanceof User) {
            return $user;
        }

        $user_id = (isset($user_id) && filled($user_id)) ? $user_id : self::convertUserToId($user);

        $user = User::query()
            ->when($mobile && filled($mobile), function ($query) use ($mobile) {
                return $query->where('mobile', $mobile);
            })
            ->when($email && filled($email), function ($query) use ($email) {
                return $query->where('email', $email);
            })
            ->when($user_id && filled($user_id), function ($query) use ($user_id) {
                return $query->where('id', $user_id);
            })
            ->when($username && filled($username), function ($query) use ($username) {
                return $query->where('username', $username);
            })
            ->when(isset($withs) && count($withs) > 0, function ($query) use ($withs, $mobile) {
                return $query->with($withs);
            });
        return $user->exists() ? $user->first() : null;
    }
    public static function prepare_users(...$users): \Illuminate\Support\Collection
    {
        # prepare users
        if (!is_array($users)) {
            $users = [$users];
        }
        $users = flatten($users);
        # standard users
        $users = (is_string($users) || is_int($users)) ? [$users] : (is_array($users) ? $users : []);
        $users = collect($users);
        # convert all users int  and validate
        $users = $users->map(function ($user) {
            return self::convertUserToId($user);
        });
        # filter null and filled
        $users = $users->filter(function ($user) {
            return isset($user) && filled($user);
        });
        # unique
        $users = $users->unique();
        return $users;
    }
}
