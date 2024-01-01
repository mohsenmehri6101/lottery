<?php

namespace Modules\Gym\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Gym\Entities\Comment;

class CommentRepository extends BaseRepository
{
    public function model(): string
    {
        return Comment::class;
    }

    public function relations(): array
    {
        return Comment::$relations_;
    }
}
