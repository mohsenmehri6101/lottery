<?php

namespace Modules\Exception\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Exception\Entities\Error;

class ErrorRepository extends BaseRepository
{

    public function model(): string
    {
        return Error::class;
    }

    public function relations(): array
    {
        return [];
    }

    public function create(array $data)
    {
        return parent::create($data);
    }
}
