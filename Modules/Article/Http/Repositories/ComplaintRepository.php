<?php

namespace Modules\Article\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Article\Entities\Complaint;

class ComplaintRepository extends BaseRepository
{
    public function model(): string
    {
        return Complaint::class;
    }

    public function relations(): array
    {
        return Complaint::$relations_;
    }

    public function fillable_search(): array
    {
        return [
            'description',
        ];
    }

}
