<?php

namespace Modules\Gym\Console;

use Illuminate\Console\Command;

class GymDeleteReservedCommand extends Command
{

    protected $signature = 'gym:delete-reserved';

    protected $description = 'Delete gym reservations with status "reserving" and past "reserved_at" time.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        deleteReservedWithStatusReserving();
        $this->info('All reserved with status reserving deleted successfully');
    }
}
