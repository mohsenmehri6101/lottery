<?php

namespace Modules\Article\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Services\UserService;
use Modules\Article\Entities\Article;
use Modules\Article\Entities\Image;
use Modules\Article\Http\Repositories\AttributeRepository;
use Modules\Article\Http\Repositories\ArticleRepository;
use Modules\Article\Http\Repositories\ReserveRepository;
use Modules\Article\Http\Requests\Article\DeleteImageArticleRequest;
use Modules\Article\Http\Requests\Article\ArticleIndexRequest;
use Modules\Article\Http\Requests\Article\ArticleLikeRequest;
use Modules\Article\Http\Requests\Article\ArticleShowRequest;
use Modules\Article\Http\Requests\Article\ArticleStoreFreeRequest;
use Modules\Article\Http\Requests\Article\ArticleStoreRequest;
use Modules\Article\Http\Requests\Article\ArticleToggleActivateRequest;
use Modules\Article\Http\Requests\Article\ArticleUpdateRequest;
use Modules\Article\Http\Requests\Article\MyArticlesRequest;
use Modules\Article\Http\Requests\Article\GetInitializeRequestsSelectors;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class ArticleService
{
    public function __construct(public ArticleRepository $articleRepository)
    {
    }
    public function indexHelper($fields=[])
    {
        /**
         * @var $min_price
         * @var $max_price
         * @var $withs
         * @var $dated_at
         * @var $sports
         * @var $attributes
         */
        extract($fields);

        $max_price = $max_price ?? null;
        $min_price = $min_price ?? null;
        $dated_at = $dated_at ?? null;
        $withs = $withs ?? [];
        $sports = $sports ?? [];
        $attributes = $attributes ?? [];
        unset($fields['withs']);
        unset($fields['min_price']);
        unset($fields['max_price']);
        unset($fields['sports']);
        unset($fields['attributes']);

        # ##########
        # attributes
        if(count($attributes)){
            $withs[]='attributes';
        }
        # sports
        if(count($sports)){
            $withs[]='sports';
        }
        if (isset($fields['dated_at']) && filled($fields['dated_at'])) {
            $withs[] = 'reserves';
        }

        $withs=array_unique($withs);

        $query = $this->articleRepository->queryFull(inputs: $fields, relations: $withs);

        $query = $query->when($max_price, function ($_query) use ($max_price) {
            return $_query->where('price', '<=', $max_price);
        })->when($min_price, function ($_query) use ($min_price) {
            return $_query->where('price', '>=', $min_price);
        });

        $query = $query->when(in_array('reserves', $withs), function ($queryReserve) use ($dated_at) {
            $queryReserve->whereHas('reserves', function (Builder $query) use ($dated_at) {
                return $query->when(filled($dated_at), function ($queryReserve) use ($dated_at) {
                    /** @var ReserveRepository $reserveRepository */
                    $reserveRepository = resolve('ReserveRepository');
                    $fields_reserves = ['dated_at' => $dated_at];
                    return $reserveRepository->queryByInputs(query: $queryReserve, inputs: $fields_reserves);
                });
            });
        });

        $query = $query->when(in_array('attributes', $withs), function ($querySport) use ($attributes) {
            $querySport->whereHas('attributes', function (Builder $query) use ($attributes) {
                return $query->when(count($attributes), function ($queryAttribute) use ($attributes) {
                    /** @var AttributeRepository $attributeRepository */
                    $attributeRepository = resolve('AttributeRepository');
                    return $attributeRepository->byArray($queryAttribute,'id',$attributes);
                });
            });
        });

        return $query;
    }
    public function toggleArticleActivated(ArticleToggleActivateRequest $request,$article_id): int
    {
        try {
            $fields = $request->validated();

            /**
             * @var $status
             */
            extract($fields);

            $status = $status ?? null;

            /** @var Article $article */
            $article = $this->articleRepository->findOrFail($article_id);

            $new_status = $status ?? $article->status === Article::status_active ? Article::status_disable : Article::status_active;

            $fields_update = ['status'=>$new_status];
            $this->articleRepository->update($article, $fields_update);

            return $new_status;

        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function index(ArticleIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $articleIndexRequest = new ArticleIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $articleIndexRequest->rules(),
                    attributes: $articleIndexRequest->attributes()
                )->validate();
            }
            else
            {
                $fields = $request->validated();
            }

            $query = $this->indexHelper($fields);

            return $this->articleRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function myArticles(MyArticlesRequest|array $request)
    {
        try {
            if (is_array($request))
            {
                $my_article_request = new MyArticlesRequest();
                $fields = Validator::make(data: $request,
                    rules: $my_article_request->rules(),
                    attributes: $my_article_request->attributes()
                )->validate();
            }
            else
            {
                $fields = $request->validated();
            }

            $user_id = get_user_id_login();

            $query = $this->indexHelper($fields);
            $query= $query
                ->whereNotNull('user_article_manager_id')
                ->where('user_article_manager_id',$user_id);
            return $this->articleRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function show(ArticleShowRequest|array $request, $article_id)
    {
        try {
            if (is_array($request)) {
                $userStoreRequest = new ArticleShowRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            $withs = convert_withs_from_string_to_array(withs: $withs);
            return $this->articleRepository->withRelations(relations: $withs)->findOrFail($article_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function store(ArticleStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $status
             * @var $tag_id
             * @var $tags
             * @var $user_article_manager_id
             * @var $category_id
             * @var $categories
             * @var $keyword_id
             * @var $keywords
             * @var $sport_id
             * @var $sports
             * @var $attribute_id
             * @var $attributes
             * @var $images
             * @var $price
             * @var $profit_share_percentage
             * @var $is_ball
             * @var $ball_price
             * @var $gender_acceptance
             * @var $time_template
             */
            extract($fields);

            unset(
                $fields['time_template'],
                $fields['tag_id'],
                $fields['tags'],
                $fields['keyword_id'],
                $fields['keywords'],
                $fields['sport_id'],
                $fields['sports'],
                $fields['attribute_id'],
                $fields['attributes'],
                $fields['category_id'],
                $fields['categories'],
            );

            $withs_result = [];

            /** @var Article $article */
            $article = $this->articleRepository->create($fields);

            # set tags
            $tag_id = $tag_id ?? null;
            $tags = $tags ?? [];
            if (isset($tag_id) && $tag_id) {
                $tags[] = $tag_id;
                $tags = array_unique($tags);
            }
            if (count($tags) > 0) {
                /** @var TagService $tagService */
                $tagService = resolve('TagService');
                $tagService->syncTagToArticle(['article_id' => $article->id, 'tags' => $tags]);
                $withs_result[] = 'tags';
            }

            # set sport
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];
            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }
            if (count($sports) > 0) {
                /** @var SportService $sportService */
                $sportService = resolve('SportService');
                $sportService->syncSportToArticle(['article_id' => $article->id, 'sports' => $sports]);
                $withs_result[] = 'sports';
            }

            # set attribute
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];
            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }
            if (count($attributes) > 0) {
                /** @var AttributeService $attributeService */
                $attributeService = resolve('AttributeService');
                $attributeService->syncAttributeToArticle(['article_id' => $article->id, 'attributes' => $attributes]);
                $withs_result[] = 'attributes';
            }

            # set keyword
            $keyword_id = $keyword_id ?? null;
            $keywords = $keywords ?? [];
            if (isset($keyword_id) && $keyword_id) {
                $keywords[] = $keyword_id;
                $keywords = array_unique($keywords);
            }

            if (count($keywords) > 0) {
                /** @var KeywordService $keywordService */
                $keywordService = resolve('KeywordService');
                $keywordService->syncKeywordToArticle(['article_id' => $article->id, 'keywords' => $keywords]);
                $withs_result[] = 'keywords';
            }

            # set categories
            $category_id = $category_id ?? null;
            $categories = $categories ?? [];
            if (isset($category_id) && $category_id) {
                $categories[] = $category_id;
                $categories = array_unique($categories);
            }

            if (count($categories) > 0) {
                /** @var CategoryService $categoryService */
                $categoryService = resolve('CategoryService');
                $categoryService->syncCategoryToArticle(['article_id' => $article->id, 'categories' => $categories]);
                $withs_result[] = 'categories';
            }

            # save images
            $images = $images ?? [];
            if (count($images)) {
                foreach ($images as $image) {
                    if ($image) {
                        # delete avatar before
                        $name_file = ImageService::setNameFile($image);
                        // todo convert image change image size and with and height
                        $path_image = $image->storeAs('articles_images', $name_file);
                        if ($path_image) {
                            $imageModel = new Image(['url' => $path_image, 'image' => $name_file]);
                            $article->images()->save($imageModel);
                        }
                    }
                }
                $withs_result[] = 'urlImages';
            }

            DB::commit();
            return $this->articleRepository->withRelations(relations: $withs_result)->findOrFail($article->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function storeFree(ArticleStoreFreeRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $mobile = $fields['mobile'] ?? null;

            # save user
            unset($fields['mobile']);

            /** @var UserService $userService */
            $userService = resolve('UserService');

            $user = $userService::getUser(mobile: $mobile, withs: ['userDetail']);
            if (!$user) {
                $user = $userService->store(['mobile' => $mobile,'status'=>User::status_inactive]);
                // todo send message sms send message
                $message = "کاربر گرامی، اطلاعات سالن ورزشی شما با موفقیت ذخیره شده است. در اسرع وقت با شما تماس گرفته خواهد شد.";
                send_sms($mobile,$message);
            }
            # save user


            $fields = [...$fields,'status'=>Article::status_not_confirm];

            $this->articleRepository->create($fields);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function like(ArticleLikeRequest $request): int
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var integer $article_id
             * @var string $type
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $type = $type ?? null;
            $likes_count = Article::like(article_id: $article_id, type: $type);

            DB::commit();
            return $likes_count;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function update(ArticleUpdateRequest $request, $article_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $status
             * @var $tag_id
             * @var $tags
             * @var $user_article_manager_id
             * @var $category_id
             * @var $categories
             * @var $keyword_id
             * @var $keywords
             * @var $sport_id
             * @var $is_ball
             * @var $ball_price
             * @var $sports
             * @var $attribute_id
             * @var $attributes
             * @var $images
             */
            extract($fields);

            unset(
                $fields['tag_id'],
                $fields['tags'],
                $fields['keyword_id'],
                $fields['keywords'],
                $fields['sport_id'],
                $fields['sports'],
                $fields['attribute_id'],
                $fields['attributes'],
                $fields['category_id'],
                $fields['categories'],
            );

            /** @var Article $article */
            $article = $this->articleRepository->findOrFail($article_id);

            $this->articleRepository->update($article, $fields);

            $withs_result = [];

            # set tags
            $tag_id = $tag_id ?? null;
            $tags = $tags ?? [];
            $tag_detach = $tag_detach ?? false;
            if (isset($tag_id) && $tag_id) {
                $tags[] = $tag_id;
                $tags = array_unique($tags);
            }
            if (count($tags) > 0) {
                /** @var TagService $tagService */
                $tagService = resolve('TagService');
                $tagService->syncTagToArticle(['article_id' => $article->id, 'tags' => $tags, 'detach' => $tag_detach]);
                $withs_result[] = 'tags';
            }
            # set categories
            $category_id = $category_id ?? null;
            $categories = $categories ?? [];
            $category_detach = $category_detach ?? false;
            if (isset($category_id) && $category_id) {
                $categories[] = $category_id;
                $categories = array_unique($categories);
            }
            if (count($categories) > 0) {
                /** @var CategoryService $categoryService */
                $categoryService = resolve('CategoryService');
                $categoryService->syncCategoryToArticle(['article_id' => $article->id, 'categories' => $categories, 'detach' => $category_detach]);
                $withs_result[] = 'categories';
            }

            # set sport
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];
            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }
            if (count($sports) > 0) {
                /** @var SportService $sportService */
                $sportService = resolve('SportService');
                $sportService->syncSportToArticle(['article_id' => $article->id, 'sports' => $sports]);
                $withs_result[] = 'sports';
            }

            # set attribute
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];
            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }
            if (count($attributes) > 0) {
                /** @var AttributeService $attributeService */
                $attributeService = resolve('AttributeService');
                $attributeService->syncAttributeToArticle(['article_id' => $article->id, 'attributes' => $attributes]);
                $withs_result[] = 'attributes';
            }

            # set keyword
            $keyword_id = $keyword_id ?? null;
            $keywords = $keywords ?? [];
            if (isset($keyword_id) && $keyword_id) {
                $keywords[] = $keyword_id;
                $keywords = array_unique($keywords);
            }
            if (count($keywords) > 0) {
                /** @var KeywordService $keywordService */
                $keywordService = resolve('KeywordService');
                $keywordService->syncKeywordToArticle(['article_id' => $article->id, 'keywords' => $keywords]);
                $withs_result[] = 'keywords';
            }

            # save images
            $images = $images ?? [];
            if (count($images)) {
                foreach ($images as $image) {
                    if ($image) {
                        # delete avatar before
                        $name_file = ImageService::setNameFile($image);
                        // todo convert image change image size and with and height
                        $path_image = $image->storeAs('articles_images', $name_file);
                        if ($path_image) {
                            $imageModel = new Image(['url' => $path_image, 'image' => $name_file]);
                            $article->images()->save($imageModel);
                        }
                    }
                }
                $withs_result[] = 'urlImages';
            }
            # save images
            DB::commit();

            return $this->articleRepository->withRelations(relations: $withs_result)->findOrFail($article->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function destroy($article_id)
    {
        DB::beginTransaction();
        try {
            # find article
            /** @var Article $article */
            $article = $this->articleRepository->findOrFail($article_id);

            # delete article
            $status_delete_article = $this->articleRepository->delete($article);

            DB::commit();
            return $status_delete_article;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function deleteImage(DeleteImageArticleRequest $request, $article_id): bool
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $image_id
             * @var $images
             */
            extract($fields);

            $image_id = $image_id ?? null;
            $images = $images ?? [];

            if (isset($image_id) && $image_id) {
                $images[] = $image_id;
                $images = array_unique($images);
            }

            # find article
            /** @var Article $article */
            $article = $this->articleRepository->findOrFail($article_id);

            # delete images from article
            $image_query = $article->images()->whereIn('images_articles.id', $images);
            $images_db = $image_query->get();
            if (count($images_db)) {
                $images_db->each(function (Image $image) {
                    $url = $image->url ?? null;
                    helperDeleteFiles($url);
                });
            }

            $image_query->delete();

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public static function updateScore($article_id): float|int
    {
        return Article::updateScore($article_id);
    }
    public function articleStatus(Request $request): array|bool|int|string|null
    {
        $status = $request->status ?? null;
        return Article::getStatusArticleTitle();
    }
    public function getInitializeRequestsSelectors(GetInitializeRequestsSelectors|array $request): array
    {
        try {
            if (is_array($request)) {
                $get_initialize_requests_selectors = new GetInitializeRequestsSelectors();
                $fields = Validator::make(data: $request,
                    rules: $get_initialize_requests_selectors->rules(),
                    attributes: $get_initialize_requests_selectors->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            $withs = array_values($withs);
            $lists = [];
            foreach ($withs as $with) {
                $cacheKey = "initialize_requests_selectors_$with";
                // Check if the data is available in the cache
                if (Cache::has($cacheKey)) {
                    $lists[$with] = Cache::get($cacheKey);
                } else {
                    switch ($with) {
                        case 'articles':
                            $lists[$with] = $this->getCachedList('ArticleService', 'index', 'articles');
                            break;
                        case 'tags':
                            $lists[$with] = $this->getCachedList('TagService', 'index', 'tags');
                            break;
                        case 'sports':
                            $lists[$with] = $this->getCachedList('SportService', 'index', 'sports');
                            break;
                        case 'keywords':
                            $lists[$with] = $this->getCachedList('KeywordService', 'index', 'keywords');
                            break;
                        case 'attributes':
                            $lists[$with] = $this->getCachedList('AttributeService', 'index', 'attributes');
                            break;
                        case 'cities':
                            $lists[$with] = $this->getCachedList('CityService', 'index', 'cities');
                            break;
                        case 'provinces':
                            $lists[$with] = $this->getCachedList('ProvinceService', 'index', 'provinces');
                            break;
                        case 'categories':
                            $lists[$with] = $this->getCachedList('CategoryService', 'index', 'categories');
                            break;
                        case 'gender_acceptances':
                            {
                                /** @var ReserveTemplateService $ReserveTemplateService */
                                $ReserveTemplateService = resolve('ReserveTemplateService');
                                $gender_acceptances = $ReserveTemplateService->gender_acceptances([]);
                                $gender_acceptances = collect($gender_acceptances)->map(function ($name, $id) {
                                    return ['id' => $id, 'name' => $name];
                                });
                                $lists[$with] = $gender_acceptances;
                            }
                            break;
                        default:
                            // Handle unknown $with values or log a warning
                            break;
                    }

                    // Set data in cache for 30 minutes
                    Cache::put($cacheKey, $lists[$with], now()->addMinutes(30));
                }
            }
            return $lists;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    private function getCachedList($serviceKey, $method, $cacheKey)
    {
        $minute_cache_time = config('configs.articles.cache_time_initialize_requests_selectors', 30);
        $service = resolve($serviceKey);
        $data = $service->$method([])->toArray()['data'];
        return Cache::remember("initialize_requests_selectors_$cacheKey", now()->addMinutes($minute_cache_time), function () use ($data) {
            return $data;
        });
    }
}
