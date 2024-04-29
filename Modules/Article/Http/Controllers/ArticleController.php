<?php

namespace Modules\Article\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Article\Entities\Article;
use Modules\Article\Http\Requests\Article\DeleteImageArticleRequest;
use Modules\Article\Http\Requests\Article\GetInitializeRequestsSelectors;
use Modules\Article\Http\Requests\Article\ArticleIndexRequest;
use Modules\Article\Http\Requests\Article\ArticleLikeRequest;
use Modules\Article\Http\Requests\Article\ArticleShowRequest;
use Modules\Article\Http\Requests\Article\ArticleStoreFreeRequest;
use Modules\Article\Http\Requests\Article\ArticleStoreRequest;
use Modules\Article\Http\Requests\Article\ArticleToggleActivateRequest;
use Modules\Article\Http\Requests\Article\ArticleUpdateRequest;
use Modules\Article\Http\Requests\Article\MyArticlesRequest;
use Modules\Article\Services\ArticleService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(public ArticleService $articleService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/articles",
     *     tags={"articles"},
     *     summary="لیست باشگاه های ورزشی",
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="user_article_manager_id",in="query",required=false, @OA\Schema(type="integer"),description="user_article_manager_id"),
     *     @OA\Parameter(name="search",in="query",required=false, @OA\Schema(type="string"),description="search"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="reason_article_disabled",in="query",required=false, @OA\Schema(type="string"),description="reason_article_disabled"),
     *     @OA\Parameter(name="gender_acceptance",in="query",required=false, @OA\Schema(type="integer"),description="gender_acceptance"),
     *     @OA\Parameter(name="priority_show",in="query",required=false, @OA\Schema(type="integer"),description="priority_show"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="min_price",in="query",required=false, @OA\Schema(type="string"),description="min_price"),
     *     @OA\Parameter(name="max_price",in="query",required=false, @OA\Schema(type="string"),description="max_price"),
     *     @OA\Parameter(name="latitude",in="query",required=false, @OA\Schema(type="string"),description="latitude"),
     *     @OA\Parameter(name="longitude",in="query",required=false, @OA\Schema(type="string"),description="longitude"),
     *     @OA\Parameter(name="city_id",in="query",required=false, @OA\Schema(type="string"),description="city_id"),
     *     @OA\Parameter(name="like_count",in="query",required=false, @OA\Schema(type="integer"),description="like_count"),
     *     @OA\Parameter(name="dislike_count",in="query",required=false, @OA\Schema(type="integer"),description="dislike_count"),
     *     @OA\Parameter(name="score",in="query",required=false, @OA\Schema(type="integer"),description="score"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="integer"),description="profit_share_percentage"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is:userCreator, userEditor, city, scores, keywords, categories, images, urlImages, tags,sports, attributes, reserveTemplates, reserves"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ArticleIndexRequest $request): JsonResponse
    {
        $articles = $this->articleService->index($request);
        return ResponseHelper::responseSuccessIndex(data: $articles);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/articles/toggle-article-activated/{id}",
     *     tags={"articles"},
     *     summary="تغییر وضعیت باشگاه(فعال|غیرفعال)",
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="article id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status:1,3"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function toggleArticleActivated(ArticleToggleActivateRequest $request,$article_id): JsonResponse
    {
        $new_status = $this->articleService->toggleArticleActivated($request,$article_id);

        $status_text = $new_status ==Article::status_active ? 'فعال' : 'غیرفعال';
        $message = "سالن با موفقیت $status_text شد";
        return ResponseHelper::responseSuccessIndex(data:['new_status'=>$new_status],message: $message);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/articles/my-articles",
     *     tags={"articles"},
     *     summary="لیست باشگاه های ورزشی من(برای مسئول سالن ورزشی)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="search",in="query",required=false, @OA\Schema(type="string"),description="search"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="reason_article_disabled",in="query",required=false, @OA\Schema(type="string"),description="reason_article_disabled"),
     *     @OA\Parameter(name="gender_acceptance",in="query",required=false, @OA\Schema(type="integer"),description="gender_acceptance"),
     *     @OA\Parameter(name="priority_show",in="query",required=false, @OA\Schema(type="integer"),description="priority_show"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="min_price",in="query",required=false, @OA\Schema(type="string"),description="min_price"),
     *     @OA\Parameter(name="max_price",in="query",required=false, @OA\Schema(type="string"),description="max_price"),
     *     @OA\Parameter(name="latitude",in="query",required=false, @OA\Schema(type="string"),description="latitude"),
     *     @OA\Parameter(name="longitude",in="query",required=false, @OA\Schema(type="string"),description="longitude"),
     *     @OA\Parameter(name="city_id",in="query",required=false, @OA\Schema(type="string"),description="city_id"),
     *     @OA\Parameter(name="like_count",in="query",required=false, @OA\Schema(type="integer"),description="like_count"),
     *     @OA\Parameter(name="dislike_count",in="query",required=false, @OA\Schema(type="integer"),description="dislike_count"),
     *     @OA\Parameter(name="score",in="query",required=false, @OA\Schema(type="integer"),description="score"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="integer"),description="profit_share_percentage"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is:userCreator, userEditor, city, scores, keywords, categories, images, urlImages, tags,sports, attributes, reserveTemplates, reserves"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myArticles(MyArticlesRequest $request): JsonResponse
    {
        $articles = $this->articleService->myArticles($request);
        return ResponseHelper::responseSuccessIndex(data: $articles);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/articles/{id}",
     *     tags={"articles"},
     *     summary="نمایش تکی باشگاه ورزشی",
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations: userCreator,userEditor,city,scores,keywords,categories,images,urlImages,tags,sports,attributes,reserveTemplates,reserves"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ArticleShowRequest $request, $article_id): JsonResponse
    {
        $article = $this->articleService->show($request, $article_id);
        return $article ? ResponseHelper::responseSuccessShow(data: $article) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/articles",
     *     tags={"articles"},
     *     summary="ذخیره باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=true, @OA\Schema(type="string"),description="Article name"),
     *     @OA\Parameter(name="reason_article_disabled",in="query",required=true, @OA\Schema(type="string"),description="reason_article_disabled"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="Article description"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="Article price"),
     *     @OA\Parameter(name="city_id",in="query",required=true, @OA\Schema(type="integer"),description="City ID"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="Article user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="Article status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="integer"),description="profit_share_percentage"),
     *     @OA\Parameter(name="priority_show",in="query",required=false, @OA\Schema(type="integer"),description="priority_show"),
     *     @OA\Parameter(name="is_ball",in="query",required=false, @OA\Schema(type="boolean"),description="is_ball"),
     *     @OA\Parameter(name="ball_price",in="query",required=false, @OA\Schema(type="string"),description="ball_price"),
     *     @OA\Parameter(name="tag_id",in="query",required=false, @OA\Schema(type="integer"),description="Tag ID"),
     *     @OA\Parameter(name="tags",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of tag IDs"),
     *     @OA\Parameter(name="category_id",in="query",required=false, @OA\Schema(type="integer"),description="Category ID"),
     *     @OA\Parameter(name="categories",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of category IDs"),
     *     @OA\Parameter(name="keyword_id",in="query",required=false, @OA\Schema(type="integer"),description="Keyword ID"),
     *     @OA\Parameter(name="keywords",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of keyword IDs"),
     *     @OA\Parameter(name="sport_id",in="query",required=false, @OA\Schema(type="integer"),description="Sport ID"),
     *     @OA\Parameter(name="sports",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of sport IDs"),
     *     @OA\Parameter(name="attribute_id",in="query",required=false, @OA\Schema(type="integer"),description="Attribute ID"),
     *     @OA\Parameter(name="attributes",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of attribute IDs"),
     *     @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={""},
     *              @OA\Property(property="images", type="array", @OA\Items(type="file", format="binary"), description="Array of image files"),
     *              @OA\Property(property="time_template", type="array", @OA\Items(
     *              @OA\Property(property="from", type="string", format="H:i", description="Start time"),
     *              @OA\Property(property="to", type="string", format="H:i", description="End time"),
     *              @OA\Property(property="break_time", type="number", format="float", description="Break time in hours"),
     *              @OA\Property(property="price", type="number", format="float", description="Price"),
     *              @OA\Property(property="gender_acceptance", type="number", description="Gender acceptance status"),
     *              @OA\Property(property="week_numbers", type="array", @OA\Items(type="integer", format="int32"), description="Array of week numbers [1,2,3,4,5,6,7]"),
     *              ), description="Array of reserve template data"),
     *          )
     *      )
     *  ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={""},
     *                 @OA\Property(property="images", type="array", @OA\Items(type="file", format="binary"), description="Array of image files")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function store(ArticleStoreRequest $request): JsonResponse
    {
        $article = $this->articleService->store($request);
        return $article ? ResponseHelper::responseSuccessStore(data: $article) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/articles/article-free",
     *     tags={"articles"},
     *     summary="ذخیره باشگاه ورزشی آزاد",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=true, @OA\Schema(type="string"),description="Article name"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="Article description"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="Article price"),
     *     @OA\Parameter(name="city_id",in="query",required=true, @OA\Schema(type="integer"),description="City ID"),
     *     @OA\Parameter(name="is_ball",in="query",required=false, @OA\Schema(type="boolean"),description="is_ball"),
     *     @OA\Parameter(name="ball_price",in="query",required=false, @OA\Schema(type="string"),description="ball_price"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={""},
     *                 @OA\Property(property="images", type="array", @OA\Items(type="file", format="binary"), description="Array of image files")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function storeFree(ArticleStoreFreeRequest $request): JsonResponse
    {
        $this->articleService->storeFree($request);
        return ResponseHelper::responseSuccessStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/articles/like",
     *     tags={"articles"},
     *     summary="لایک/دیسلایک باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="article_id",in="query",required=true, @OA\Schema(type="integer"),description="article_id"),
     *     @OA\Parameter(name="type",in="query",required=true, @OA\Schema(type="string"),description="type:like,dislike"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function like(ArticleLikeRequest $request): JsonResponse
    {
        $likes_count = $this->articleService->like($request);
        return ResponseHelper::responseSuccess(data: ['likes_count' => $likes_count]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/articles/{id}",
     *     tags={"articles"},
     *     summary="ویرایش باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="reason_article_disabled",in="query",required=false, @OA\Schema(type="string"),description="reason_article_disabled"),
     *     @OA\Parameter(name="description",in="query",required=false, @OA\Schema(type="string"),description="description"),
     *     @OA\Parameter(name="price",in="query",required=false, @OA\Schema(type="string"),description="price"),
     *     @OA\Parameter(name="city_id",in="query",required=false, @OA\Schema(type="string"),description="city_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Parameter(name="profit_share_percentage",in="query",required=false, @OA\Schema(type="string"),description="profit_share_percentage"),
     *     @OA\Parameter(name="gender_acceptance",in="query",required=false, @OA\Schema(type="string"),description="gender_acceptance"),
     *     @OA\Parameter(name="priority_show",in="query",required=false, @OA\Schema(type="integer"),description="priority_show"),
     *     @OA\Parameter(name="is_ball",in="query",required=false, @OA\Schema(type="boolean"),description="is_ball"),
     *     @OA\Parameter(name="ball_price",in="query",required=false, @OA\Schema(type="string"),description="ball_price"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="string"),description="user_id"),
     *     @OA\Parameter(name="tag_id",in="query",required=false, @OA\Schema(type="integer"),description="Tag ID"),
     *     @OA\Parameter(name="tags",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of tag IDs"),
     *     @OA\Parameter(name="category_id",in="query",required=false, @OA\Schema(type="integer"),description="Category ID"),
     *     @OA\Parameter(name="categories",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of category IDs"),
     *     @OA\Parameter(name="keyword_id",in="query",required=false, @OA\Schema(type="integer"),description="Keyword ID"),
     *     @OA\Parameter(name="keywords",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of keyword IDs"),
     *     @OA\Parameter(name="sport_id",in="query",required=false, @OA\Schema(type="integer"),description="Sport ID"),
     *     @OA\Parameter(name="sports",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of sport IDs"),
     *     @OA\Parameter(name="attribute_id",in="query",required=false, @OA\Schema(type="integer"),description="Attribute ID"),
     *     @OA\Parameter(name="attributes",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="integer")),description="Array of attribute IDs"),
     *     @OA\Parameter(name="images",in="query",required=false, @OA\Schema(type="array", @OA\Items(type="file", format="binary"), description="Array of image files")),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={""},
     *              @OA\Property(property="images", type="array", @OA\Items(type="file", format="binary"), description="Array of image files"),
     *         )
     *       )
     *     ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ArticleUpdateRequest $request, $article_id): JsonResponse
    {
        $article = $this->articleService->update($request, $article_id);
        return $article ? ResponseHelper::responseSuccessUpdate(data: $article) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/articles/{id}",
     *     tags={"articles"},
     *     summary="حذف باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($article_id): JsonResponse
    {
        $status_delete = $this->articleService->destroy($article_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/articles/delete-image/{id}",
     *     tags={"articles"},
     *     summary="حذف عکس باشگاه ورزشی",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="article id"),
     *     @OA\Parameter(name="images",in="query",required=false, @OA\Schema(type="string"),description="image ids"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function deleteImage(DeleteImageArticleRequest $request, $article_id): JsonResponse
    {
        $status_delete = $this->articleService->deleteImage($request, $article_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/articles/article-status",
     *     tags={"articles"},
     *     summary="لیست وضعیت های مختلف مختلف باشگاه ورزشی",
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function articlesStatus(Request $request): JsonResponse
    {
        $articles_status = $this->articleService->articleStatus($request);
        $articles_status = collect($articles_status)->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        });
        return ResponseHelper::responseSuccess(data: $articles_status);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/get-initialize-requests-selectors",
     *     tags={"articles"},
     *     summary="Get initialization data for selectors",
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:articles,tags,categories,sports,attributes,keywords,cities,provinces,gender_acceptances"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function getInitializeRequestsSelectors(GetInitializeRequestsSelectors $request): JsonResponse
    {
        $lists = $this->articleService->getInitializeRequestsSelectors($request);
        return ResponseHelper::responseSuccess(data: $lists);
    }

}
