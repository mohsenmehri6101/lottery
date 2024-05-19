<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\CommonComplaint;

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
