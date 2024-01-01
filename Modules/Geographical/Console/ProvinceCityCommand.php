<?php

namespace Modules\Geographical\Console;

use Illuminate\Console\Command;

class ProvinceCityCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $signature = 'provinces:insert';

    protected $description = 'insert all provinces';

    public function handle()
    {
        echo "list provinces :\n";
        // todo
//        $provinces = Province::query()->insert(
//        );
//        $cities_center = City::query()->insert();
//        $cities = City::query()->insert();
        echo "*************\n";
        echo "done";
        return Command::SUCCESS;
    }

}
