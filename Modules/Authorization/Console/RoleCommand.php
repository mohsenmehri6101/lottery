<?php

namespace Modules\Authorization\Console;

use App\Permissions\Roles;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Modules\Authorization\Entities\Role;
use Modules\Authorization\Http\Repositories\RoleRepository;
use Modules\Authorization\Services\GroupService;
use Symfony\Component\Console\Output\ConsoleOutput;

class RoleCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $signature = 'roles:insert';

    protected $description = 'insert (first or create) all roles in table roles and set group_role';

    public function handle()
    {
        $output = new ConsoleOutput();
//        Artisan::call("group_roles:insert", [], $output);

        echo "list roles :\n";
        echo "*************\n\n";
        echo "نام\t\t\tنام فارسی\t\t\tگروه کاربران";
        foreach (Roles::roles() as $role) {
            /**
             * @var string $name
             * @var string $persian_name
             * @var string $group
             */
            extract($role);

            $name = $name ?? null;
            $persian_name = $persian_name ?? null;
            $group = $group ?? null;

            /** @var RoleRepository $roleRepository */
            $roleRepository = resolve('RoleRepository');

            # first or create role
            /** @var Role $role */
            $role = $roleRepository->firstOrCreate(
                [
                    'name' => $name
                ],
                [
                    'name' => $name,
                    'persian_name' => $persian_name,
                ]
            );

            # about group_role
            /** @var GroupService $groupService */
            $groupService = resolve('GroupRoleService');

            try {
                $group_id = GroupService::convertGroupToId(trim($group));
                $group_status = $group_id ? $groupService->syncRoleToGroup([
                    'role_id' => $role?->id,
                    'group_id' => $group_id,
                    'detach' => false,
                ]) : false;
            } catch (Exception $exception) {
                report($exception);
            }

            echo "$name\t\t\t$persian_name\t\t\t$group";
            echo "\n";
        }
        echo "*************\n";
        echo "done";
        return Command::SUCCESS;
    }

}
