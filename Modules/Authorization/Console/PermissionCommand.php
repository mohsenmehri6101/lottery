<?php

namespace Modules\Authorization\Console;

use Illuminate\Console\Command;
use Modules\Authorization\Http\Repositories\PermissionRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PermissionCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $signature = 'permissions:insert';

    protected $description = 'insert (first or create) all permissions in table permissions';

    public function handle()
    {
        echo "list permissions :\n";
        $permissions = config('permissions',[]);
        $list = [];
        echo "*************\n";
        foreach ($permissions as $permission) {
            $permissions_ = $permission::get_values_keys() ?? [];
            foreach ($permissions_ as $permission_key => $permission_value) {
                $list[$permission_key] = $permission_value;

                /** @var PermissionRepository $permissionRepository */
                $permissionRepository = resolve('PermissionRepository');

                # first or create permission
                $state=$permissionRepository->firstOrCreate(
                    [
                        'name' => $permission_key
                    ], [
                    'name' => $permission_key,
                    'persian_name' => $permission_value,
                ]);

                echo "$permission_key\t\t\t$permission_value\n";
            }
        }
        echo "*************\n";
        echo "done";
        return Command::SUCCESS;
    }

    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
