<?php

namespace Modules\Exception\Console;

use Illuminate\Console\Command;
use Modules\Exception\Entities\ExceptionModel;

class InsertExceptionCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $signature = 'exceptions:insert';

    protected $description = 'Command description';

    public function handle()
    {
        $exceptions = config('exceptions.exceptions',[]);
        echo "*************\n";
        foreach ($exceptions as $exception) {
            $attributes = ['exception'=>$exception['exception'] ?? ''];
            echo "exception:\t ".$exception['exception']."\t\tmessage:\t".$exception['message']."\n";
            $values=$exception;
            ExceptionModel::query()->firstOrCreate($attributes,$values);
        }
        echo "*************\n";
        echo "done";
        return Command::SUCCESS;
    }
}
