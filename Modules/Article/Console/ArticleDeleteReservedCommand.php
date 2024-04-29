<?php

namespace Modules\Article\Console;

use Illuminate\Console\Command;

class ArticleDeleteReservedCommand extends Command
{
    protected $signature = 'article:delete-reserved';

    protected $description = 'Delete article reservations with status "reserving" and past "reserved_at" time.';

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
