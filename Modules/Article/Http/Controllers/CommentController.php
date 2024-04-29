<?php

namespace Modules\Article\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Comment\CommentIndexRequest;
use Modules\Article\Http\Requests\Comment\CommentLikeRequest;
use Modules\Article\Http\Requests\Comment\CommentShowRequest;
use Modules\Article\Http\Requests\Comment\CommentStoreRequest;
use Modules\Article\Http\Requests\Comment\CommentUpdateRequest;
use Modules\Article\Http\Requests\Comment\MyCommentRequest;
use Modules\Article\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function __construct(public CommentService $commentService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/comments",
     *     tags={"comments"},
     *     summary="list comments",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="text",in="query",required=false, @OA\Schema(type="string"),description="text"),
     *     @OA\Parameter(name="article_id",in="query",required=false, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(CommentIndexRequest $request): JsonResponse
    {
        $comments = $this->commentService->index($request);
        return ResponseHelper::responseSuccess(data: $comments);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/comments/my-comments",
     *     tags={"comments"},
     *     summary="list comments",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="text",in="query",required=false, @OA\Schema(type="string"),description="text"),
     *     @OA\Parameter(name="article_id",in="query",required=false, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="selects",in="query",required=false, @OA\Schema(type="string"),description="selects:id, text, parent, status, user_creator, user_editor, created_at, updated_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myComments(MyCommentRequest $request): JsonResponse
    {
        $comments = $this->commentService->myComments($request);
        return ResponseHelper::responseSuccess(data: $comments);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/comments/{id}",
     *     tags={"comments"},
     *     summary="show comment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(CommentShowRequest $request, $comment_id): JsonResponse
    {
        $comment = $this->commentService->show($request, $comment_id);
        return $comment ? ResponseHelper::responseSuccessShow(data: $comment) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/comments",
     *     tags={"comments"},
     *     summary="save comment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="text",in="query",required=true, @OA\Schema(type="string"),description="text"),
     *     @OA\Parameter(name="article_id",in="query",required=false, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(CommentStoreRequest $request): JsonResponse
    {
        $comment = $this->commentService->store($request);
        return $comment ? ResponseHelper::responseSuccessStore(data: $comment) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/comments/like",
     *     tags={"comments"},
     *     summary="like comment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="comment_id",in="query",required=true, @OA\Schema(type="integer"),description="comment_id"),
     *     @OA\Parameter(name="type",in="query",required=true, @OA\Schema(type="string"),description="type:like,dislike"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function like(CommentLikeRequest $request): JsonResponse
    {
        $likes_count = $this->commentService->like($request);
        return ResponseHelper::responseSuccess(data: ['likes_count'=>$likes_count]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/comments/{id}",
     *     tags={"comments"},
     *     summary="update comment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *     @OA\Parameter(name="text",in="query",required=true, @OA\Schema(type="string"),description="text"),
     *     @OA\Parameter(name="article_id",in="query",required=false, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="parent",in="query",required=false, @OA\Schema(type="integer"),description="parent"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(CommentUpdateRequest $request, $comment_id): JsonResponse
    {
        $comment = $this->commentService->update($request, $comment_id);
        return $comment ? ResponseHelper::responseSuccessUpdate(data: $comment) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/comments/{id}",
     *     tags={"comments"},
     *     summary="delete comment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($comment_id): JsonResponse
    {
        $status_delete = $this->commentService->destroy($comment_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
