<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Http\Requests\Comment\CommentIndexRequest;
use Modules\Gym\Http\Requests\Comment\CommentLikeRequest;
use Modules\Gym\Http\Requests\Comment\CommentShowRequest;
use Modules\Gym\Http\Requests\Comment\CommentStoreRequest;
use Modules\Gym\Http\Requests\Comment\CommentUpdateRequest;
use Modules\Gym\Entities\Comment;
use Modules\Gym\Http\Repositories\CommentRepository;
use Modules\Gym\Http\Requests\Comment\MyCommentRequest;

class CommentService
{
    public function __construct(public CommentRepository $commentRepository)
    {
    }

    public function index(CommentIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
            return $this->commentRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function myComments(MyCommentRequest|array $request)
    {
        try {
            $fields = $request->validated();

            $user_logged_in = get_user_id_login();
            $inputs_force_my_comment = ['user_creator' => $user_logged_in/*,'user_editor'=>$user_logged_in*/];
            $fields = array_merge($fields, $inputs_force_my_comment);
            $selects = $fields['selects'] ?? [];
            unset($fields['selects']);

            return $this->commentRepository->resolve_paginate(inputs: $fields, selects: $selects);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(CommentShowRequest $request, $comment_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->commentRepository->withRelations(relations: $withs)->findOrFail($comment_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(CommentStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $comment = $this->commentRepository->create($fields);
            DB::commit();
            return $comment;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function like(CommentLikeRequest $request)
    {
        try {
            $fields = $request->validated();

            /**
             * @var integer $comment_id
             * @var string $type
             */
            extract($fields);

            $comment_id = $comment_id ?? null;
            $type = $type ?? null;
            $likes_count = Comment::like(comment_id: $comment_id,type: $type);

            return $likes_count;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function update(CommentUpdateRequest $request, $comment_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Comment $comment */
            $comment = $this->commentRepository->findOrFail($comment_id);

            $this->commentRepository->update($comment, $fields);
            DB::commit();

            return $this->commentRepository->findOrFail($comment_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($comment_id)
    {
        DB::beginTransaction();
        try {
            # find comment
            /** @var Comment $comment */
            $comment = $this->commentRepository->findOrFail($comment_id);

            # todo comments delete
            # if comment won't be deleting and have children?:
            # # or not should be deleted
            # # or delete all children too.

            # delete comment
            $status_delete_comment = $this->commentRepository->delete($comment);

            DB::commit();
            return $status_delete_comment;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
