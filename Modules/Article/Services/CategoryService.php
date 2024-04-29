<?php

namespace Modules\Article\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Article\Http\Requests\Category\CategoryIndexRequest;
use Modules\Article\Http\Requests\Category\CategoryShowRequest;
use Modules\Article\Http\Requests\Category\CategoryStoreRequest;
use Modules\Article\Http\Requests\Category\CategoryUpdateRequest;
use Modules\Article\Entities\Category;
use Modules\Article\Entities\Article;
use Modules\Article\Http\Repositories\CategoryRepository;
use Modules\Article\Http\Requests\Category\DeleteCategoryToArticleRequest;
use Modules\Article\Http\Requests\Category\SyncCategoryToArticleRequest;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(public CategoryRepository $categoryRepository)
    {
    }

    public function index(CategoryIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $categoryIndexRequest = new CategoryIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $categoryIndexRequest->rules(),
                    attributes: $categoryIndexRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            return $this->categoryRepository->resolve_paginate(
                inputs: $fields,
                orderByColumn: $fields['order_by'] ?? 'id',
                directionOrderBy: $fields['direction_by'] ?? 'desc'
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(CategoryShowRequest $request, $category_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->categoryRepository->withRelations(relations: $withs)->findOrFail($category_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $category = $this->categoryRepository->create($fields);
            DB::commit();
            return $category;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(CategoryUpdateRequest $request, $category_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Category $category */
            $category = $this->categoryRepository->findOrFail($category_id);

            $this->categoryRepository->update($category, $fields);
            DB::commit();

            return $this->categoryRepository->findOrFail($category_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($category_id)
    {
        DB::beginTransaction();
        try {
            # find category
            /** @var Category $category */
            $category = $this->categoryRepository->findOrFail($category_id);

            # delete category
            $status_delete_category = $this->categoryRepository->delete($category);

            DB::commit();
            return $status_delete_category;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncCategoryToArticle(SyncCategoryToArticleRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $sync_category_to_article_request = new SyncCategoryToArticleRequest();
                $fields = Validator::make(data: $request,
                    rules: $sync_category_to_article_request->rules(),
                    attributes: $sync_category_to_article_request?->attributes() ?? [],
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $article_id
             * @var $detach
             * @var $category_id
             * @var $categories
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $detach = $detach ?? false;
            $category_id = $category_id ?? null;
            $categories = $categories ?? [];

            if (isset($category_id) && $category_id) {
                $categories[] = $category_id;
                $categories = array_unique($categories);
            }

            # find article
            /** @var Article $article */
            $article = Article::query()->findOrFail($article_id);
            // todo use resolve('ArticleService');

            # sync category to article
            $article->categories()->sync($categories, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteCategoryToArticle(DeleteCategoryToArticleRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $article_id
             * @var $touch
             * @var $category_id
             * @var $categories
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $touch = $touch ?? true;
            $category_id = $category_id ?? null;
            $categories = $categories ?? [];

            if (isset($category_id) && $category_id) {
                $categories[] = $category_id;
                $categories = array_unique($categories);
            }

            # find article
            /** @var Article $article */
            $article = Article::query()->findOrFail($article_id);

            # detach category to article
            $article->categories()->detach($categories, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public static function prepare_categories(...$categories)
    {
        # prepare categories
        if (!is_array($categories) && (is_string($categories) || is_int($categories))) {
            $categories = [$categories];
        }

        if (is_array($categories)) {
            $categories = flatten($categories);
        }

        # standard categories
        $categories = (is_string($categories) || is_int($categories)) ? [$categories] : (is_array($categories) ? $categories : []);
        $categories = collect($categories);
        # convert all categories int  and validate
        $categories = $categories->map(function ($category) {
            $id = self::convertCategoryToId($category);
            return $id;
        });
        # filter null and filled
        $categories = $categories->filter(function ($category) {
            return isset($category) && filled($category);
        });
        # unique
        $categories = $categories->unique();

        return $categories;
    }

    public static function convertCategoryToId($category = null)
    {
        if (is_null($category)) {
            return $category;
        }

        if ($category instanceof Collection) {
            $category = $category->toArray() ?? [];
        }

        if ($category instanceof Category) {
            return $category?->id ?? null;
        } elseif (is_array($category) && isset($category['id']) && filled($category['id'])) {
            return $category['id'] ?? null;
        } elseif (is_numeric($category)) {
            return $category;
        }

        /** @var Category $category */
        $category = $category && filled($category) ? Category::query()
            ->where('name', $category)
            ->orWhere('id', $category) : null;

        return ($category && $category->exists()) ? $category->first()?->id : $category;
    }

}
