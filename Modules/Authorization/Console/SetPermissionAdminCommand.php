<?php

namespace Modules\Authorization\Console;

use App\Permissions\Roles;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Modules\Authorization\Entities\Permission;
use Modules\Authorization\Entities\Role;
use Symfony\Component\Console\Output\ConsoleOutput;
use Exception;

class SetPermissionAdminCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $signature = 'permissions:set_all_permissions_super_admin';

    protected $description = 'set all permissions from role : super_admin(every user have role super_admin)';

    public function handle()
    {
        try {
            $output = new ConsoleOutput();
            Artisan::call("permissions:insert", [], $output);
            Artisan::call("roles:insert", [], $output);

            $permissions = config('permissions', []);
            echo "*************\n";
            $all_permissions = [];
            foreach ($permissions as $permission) {
                # set all permissions in configs permissionsEnum
                $all_permissions = array_merge($all_permissions, $permission::names());
                # set all permissions in db too
                $all_permissions_in_db = Permission::query()->get()?->pluck('name')?->toArray() ?? [];
                $all_permissions = array_merge($all_permissions_in_db, $all_permissions);
                $all_permissions = array_unique($all_permissions);
            }

            /** @var Role $role_super_admin */
            $role_super_admin = Role::query()->where('name', Roles::super_admin)->first();

            $role_super_admin->givePermissionTo($all_permissions);
            $role_super_admin->save();
            echo "###########\n";
            echo "all user have role\t" . $role_super_admin?->name . "(" . $role_super_admin?->persian_name . ")" . "\n";
            echo "set permissions :\n\t";
            echo implode('  -  ', $all_permissions);
            echo "\n###########\n";
            echo "*************\n";
            echo "done";
            return Command::SUCCESS;
        }
        catch (Exception $exception){
            report($exception);
        }
    }
}
