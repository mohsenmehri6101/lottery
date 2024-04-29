<?php

namespace Modules\Faq\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Faq\Entities\Faq;

class FaqRepository extends BaseRepository
{
    public function model(): string
    {
        return Faq::class;
    }

    public function relations(): array
    {
        return Faq::$relations_;
    }
}
