<?php

use App\Permissions\RolesEnum;
use Illuminate\Support\Facades\Artisan;
use Modules\Authorization\Entities\Role;
use Modules\Gym\Database\Seeders\GymDatabaseSeeder;
use Symfony\Component\Console\Output\ConsoleOutput;

if (!function_exists('insertFakeUser')) {
    function insertFakeUser(): void
    {
        $image_directory = public_path('faker_avatars/');
        $image_files = glob($image_directory . '*.{jpg,jpeg}', GLOB_BRACE);

        /** @var Modules\Authentication\Entities\User $userAdmin */
        $userAdmin = Modules\Authentication\Entities\User::query()->create(
            [
                'password' => '123456789',
                'username' => 'mohsen6101',
                'email' => 'mohsen.mehri6101@gmail.com',
                'mobile' => '09366246101',
                'status' => Modules\Authentication\Entities\User::status_active,
                'mobile_verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $random_image_admin = GymDatabaseSeeder::select_random_avatar_image();
        GymDatabaseSeeder::helperFunctionSaveAvatar($userAdmin, $random_image_admin);

        $userAdmin->userDetail()->create(
            [
                'name' => 'محسن',
                'family' => 'مهری',
                'father' => 'علی',
                'national_code' => '0630196281',
                'gender' => Modules\Authentication\Entities\UserDetail::gender_male,
                'birthday' => '1994-03-21',
            ]
        );

        /** @var Modules\Authentication\Entities\User $userGymManager */
        $userGymManager = Modules\Authentication\Entities\User::query()->create(
            [
                'password' => '123456789',
                'username' => 'hadi6101',
                'email' => 'hadi.yusefi6101@gmail.com',
                'mobile' => '09366246102',
                'status' => Modules\Authentication\Entities\User::status_active,
                'mobile_verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $random_image_gym_manager = GymDatabaseSeeder::select_random_avatar_image();
        GymDatabaseSeeder::helperFunctionSaveAvatar($userGymManager, $random_image_gym_manager);

        $userGymManager->userDetail()->create(
            [
                'name' => 'هادی',
                'family' => 'یوسفی',
                'father' => 'علی',
                'national_code' => '0641146384',
                'gender' => Modules\Authentication\Entities\UserDetail::gender_male,
                'birthday' => '1984-03-21',
            ]
        );

        /** @var Modules\Authentication\Entities\User $user */
        $user = Modules\Authentication\Entities\User::query()->create(
            [
                'password' => '123456789',
                'username' => 'masud6101',
                'email' => 'masud.mehri6103@gmail.com',
                'mobile' => '09366246103',
                'status' => Modules\Authentication\Entities\User::status_active,
                'mobile_verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $random_image = GymDatabaseSeeder::select_random_avatar_image();
        GymDatabaseSeeder::helperFunctionSaveAvatar($user, $random_image);

        $user->userDetail()->create(
            [
                'name' => 'مسعود',
                'family' => 'مهری',
                'father' => 'علی',
                'national_code' => '0641116385',
                'gender' => Modules\Authentication\Entities\UserDetail::gender_male,
                'birthday' => '1984-03-21',
            ]
        );

        foreach (RolesEnum::cases() as $enumItem) {
            /** @var Role $role */
            $role = Role::query()->create([
                'name' => $enumItem->name,
                'persian_name' => $enumItem->value,
            ]);
        }

        $role_admin_id = Role::query()->where('name', RolesEnum::admin->name)->first()->id;
        $role_gym_manager_id = Role::query()->where('name', RolesEnum::gym_manager->name)->first()->id;
        $role_user_id = Role::query()->where('name', RolesEnum::user->name)->first()->id;

        $userAdmin->roles()->attach([$role_admin_id]);
        $userGymManager->roles()->attach([$role_gym_manager_id]);
        $user->roles()->attach([$role_user_id]);

        // run in here command php artisan module:seed Gym
        $output = new ConsoleOutput();
        Artisan::call("module:seed Gym", [], $output);
    }

}
