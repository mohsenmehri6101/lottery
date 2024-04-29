<?php

namespace Modules\Article\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Keyword\KeywordIndexRequest;
use Modules\Article\Http\Requests\Keyword\KeywordShowRequest;
use Modules\Article\Http\Requests\Keyword\KeywordStoreRequest;
use Modules\Article\Http\Requests\Keyword\KeywordUpdateRequest;
use Modules\Article\Http\Requests\Keyword\DeleteKeywordToArticleRequest;
use Modules\Article\Http\Requests\Keyword\SyncKeywordToArticleRequest;
use Modules\Article\Services\KeywordService;
use Illuminate\Http\JsonResponse;

class KeywordController extends Controller
{
    public function __construct(public KeywordService $keywordService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/keywords",
     *     tags={"keywords"},
     *     summary="list keywords",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="keyword",in="query",required=false, @OA\Schema(type="string"),description="keyword"),
     *     @OA\Parameter(name="slug",in="query",required=false, @OA\Schema(type="string"),description="slug"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(KeywordIndexRequest $request): JsonResponse
    {
        $keywords = $this->keywordService->index($request);
        return ResponseHelper::responseSuccess(data: $keywords);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/keywords/{id}",
     *     tags={"keywords"},
     *     summary="show keyword",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(KeywordShowRequest $request, $keyword_id)
    {
        $keyword = $this->keywordService->show($request, $keyword_id);
        return $keyword ? ResponseHelper::responseSuccessShow(data: $keyword) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/keywords",
     *     tags={"keywords"},
     *     summary="save keyword",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="keyword",in="query",required=true, @OA\Schema(type="string"),description="keyword"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(KeywordStoreRequest $request)
    {
        $keyword = $this->keywordService->store($request);
        return $keyword ? ResponseHelper::responseSuccessStore(data: $keyword) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/keywords/{id}",
     *     tags={"keywords"},
     *     summary="update keyword",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="keyword",in="query",required=false, @OA\Schema(type="string"),description="keyword"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(KeywordUpdateRequest $request, $keyword_id)
    {
        $keyword = $this->keywordService->update($request, $keyword_id);
        return $keyword ? ResponseHelper::responseSuccessUpdate(data: $keyword) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/keywords/{id}",
     *     tags={"keywords"},
     *     summary="delete keyword",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($keyword_id)
    {
        $status_delete = $this->keywordService->destroy($keyword_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/keywords/sync-keyword-to-article",
     *     tags={"keywords"},
     *     summary="sync keyword to article",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="article_id",in="query",required=true, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="keyword_id",in="query",required=false, @OA\Schema(type="string"),description="keyword_id"),
     *     @OA\Parameter(name="keywords",in="query",required=false, @OA\Schema(type="string"),description="keywords"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function syncKeywordToArticle(SyncKeywordToArticleRequest $request)
    {
        $status = $this->keywordService->syncKeywordToArticle($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/keywords/delete-keyword-to-article",
     *     tags={"keywords"},
     *     summary="delete keyword to article",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="article_id",in="query",required=true, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="detach",in="query",required=false, @OA\Schema(type="integer"),description="detach"),
     *     @OA\Parameter(name="keyword_id",in="query",required=false, @OA\Schema(type="string"),description="keyword_id"),
     *     @OA\Parameter(name="keywords",in="query",required=false, @OA\Schema(type="string"),description="keywords"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteKeywordToArticle(DeleteKeywordToArticleRequest $request)
    {
        $status = $this->keywordService->deleteKeywordToArticle($request);
        return $status ? ResponseHelper::responseSuccess() : ResponseHelper::responseFailed();
    }

}
