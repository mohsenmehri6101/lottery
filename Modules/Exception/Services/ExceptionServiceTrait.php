<?php

namespace Modules\Exception\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Exception\Entities\ExceptionModel;

trait ExceptionServiceTrait
{
    private static function save_exception($exception_name): \Illuminate\Database\Eloquent\Model|Builder|null
    {
        return ExceptionModel::query()->firstOrCreate(['exception' => $exception_name]) ?? null;
    }
}
