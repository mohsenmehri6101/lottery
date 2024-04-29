<?php

namespace Modules\Article\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Article\Entities\CommonComplaint;

class CommonComplaintRepository extends BaseRepository
{
    public function model(): string
    {
        return CommonComplaint::class;
    }

    public function relations(): array
    {
        return CommonComplaint::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'text',
        ];
    }

}
