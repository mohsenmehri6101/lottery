<?php

namespace Modules\ContactUs\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\ContactUs\Entities\ContactUs;

class ContactUsRepository extends BaseRepository
{
    public function model(): string
    {
        return ContactUs::class;
    }

    public function relations(): array
    {
        return ContactUs::$relations_;
    }

}
