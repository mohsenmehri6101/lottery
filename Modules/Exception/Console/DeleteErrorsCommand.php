<?php

namespace Modules\Exception\Console;

use Illuminate\Console\Command;
use Modules\Exception\Entities\Error;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeleteErrorsCommand extends Command
{
    protected $signature = 'exception:delete-errors {--from= : Starting date} {--to= : Ending date}';
    protected $description = 'Delete soft-deleted errors based on date range or delete all soft-deleted errors';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $from = $this->option('from');
        $to = $this->option('to');

        if ($from && $to) {
            $this->deleteBetweenDates($from, $to);
        } elseif ($from) {
            $this->deleteFromToDate($from);
        } elseif ($to) {
            $this->deleteToDate($to);
        } else {
            $this->deleteAll();
        }
    }

    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

    /* ------------------ do brother do ------------------------------- */
    private function deleteBetweenDates($from, $to): void
    {
        Error::query()->whereBetween('deleted_at', [$from, $to])->forceDelete();
        $this->info('Deleted soft-deleted errors between ' . $from . ' and ' . $to);
    }
    private function deleteFromToDate($from): void
    {
        Error::query()->where('deleted_at', '>=', $from)->forceDelete();
        $this->info('Deleted soft-deleted errors from ' . $from . ' till now');
    }
    private function deleteToDate($to): void
    {
        Error::query()->where('deleted_at', '<=', $to)->forceDelete();
        $this->info('Deleted soft-deleted errors till ' . $to);
    }
    private function deleteAll(): void
    {
        Error::query()->onlyTrashed()->forceDelete();
        $this->info('Deleted all soft-deleted errors');
    }

    /* ------------------ do brother do ------------------------------- */
}
