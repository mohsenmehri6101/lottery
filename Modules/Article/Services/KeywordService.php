<?php

namespace Modules\Article\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Article\Http\Requests\Keyword\KeywordIndexRequest;
use Modules\Article\Http\Requests\Keyword\KeywordShowRequest;
use Modules\Article\Http\Requests\Keyword\KeywordStoreRequest;
use Modules\Article\Http\Requests\Keyword\KeywordUpdateRequest;
use Modules\Article\Entities\Keyword;
use Modules\Article\Entities\Article;
use Modules\Article\Http\Repositories\KeywordRepository;
use Modules\Article\Http\Requests\Keyword\DeleteKeywordToArticleRequest;
use Modules\Article\Http\Requests\Keyword\SyncKeywordToArticleRequest;
use Illuminate\Support\Collection;

class KeywordService
{
    public function __construct(public KeywordRepository $keywordRepository)
    {
    }

    public function index(KeywordIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $keywordStoreRequest = new KeywordIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $keywordStoreRequest->rules(),
                    attributes: $keywordStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }
            return $this->keywordRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(KeywordShowRequest $request, $keyword_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->keywordRepository->withRelations(relations: $withs)->findOrFail($keyword_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(KeywordStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $keyword = $this->keywordRepository->create($fields);
            DB::commit();
            return $keyword;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(KeywordUpdateRequest $request, $keyword_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Keyword $keyword */
            $keyword = $this->keywordRepository->findOrFail($keyword_id);

            $this->keywordRepository->update($keyword, $fields);
            DB::commit();

            return $this->keywordRepository->findOrFail($keyword_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($keyword_id)
    {
        DB::beginTransaction();
        try {
            # find keyword
            /** @var Keyword $keyword */
            $keyword = $this->keywordRepository->findOrFail($keyword_id);

            # delete keyword
            $status_delete_keyword = $this->keywordRepository->delete($keyword);

            DB::commit();
            return $status_delete_keyword;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncKeywordToArticle(SyncKeywordToArticleRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new SyncKeywordToArticleRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules(),
                    attributes: $loginRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $article_id
             * @var $detach
             * @var $keyword_id
             * @var $keywords
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $detach = $detach ?? false;
            $keyword_id = $keyword_id ?? null;
            $keywords = $keywords ?? [];

            if (isset($keyword_id) && $keyword_id) {
                $keywords[] = $keyword_id;
                $keywords = array_unique($keywords);
            }

            # find article
            /** @var Article $article */
            $article = Article::query()->findOrFail($article_id);

            # sync keyword to article
            $article->keywords()->sync($keywords, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteKeywordToArticle(DeleteKeywordToArticleRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $article_id
             * @var $touch
             * @var $keyword_id
             * @var $keywords
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $touch = $touch ?? true;
            $keyword_id = $keyword_id ?? null;
            $keywords = $keywords ?? [];

            if (isset($keyword_id) && $keyword_id) {
                $keywords[] = $keyword_id;
                $keywords = array_unique($keywords);
            }

            # find article
            /** @var Article $article */
            $article = Article::query()->findOrFail($article_id);

            # detach keyword to article
            $article->keywords()->detach($keywords, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public static function prepare_keywords(...$keywords)
    {
        # prepare keywords
        if (!is_array($keywords) && (is_string($keywords) || is_int($keywords))) {
            $keywords = [$keywords];
        }

        if (is_array($keywords)) {
            $keywords = flatten($keywords);
        }

        # standard keywords
        /** @var array $keywords */
        $keywords = (is_string($keywords) || is_int($keywords)) ? [$keywords] : (is_array($keywords) ? $keywords : []);
        /** @var collection $keywords */
        $keywords = collect($keywords);
        # convert all keywords int  and validate
        $keywords = $keywords->map(function ($keyword) {
            $id = self::convertKeywordToId($keyword);
            return $id;
        });
        # filter null and filled
        $keywords = $keywords->filter(function ($keyword) {
            return isset($keyword) && filled($keyword);
        });
        # unique
        return $keywords->unique();
    }

    public static function convertKeywordToId($keyword = null)
    {
        if (is_null($keyword)) {
            return $keyword;
        }

        if ($keyword instanceof Collection) {
            $keyword = $keyword->toArray() ?? [];
        }

        if ($keyword instanceof Keyword) {
            return $keyword?->id ?? null;
        } elseif (is_array($keyword) && isset($keyword['id']) && filled($keyword['id'])) {
            return $keyword['id'] ?? null;
        } elseif (is_numeric($keyword)) {
            return $keyword;
        }

        /** @var Keyword $keyword */
        $keyword = $keyword && filled($keyword) ? Keyword::query()
            ->where('keyword', $keyword)
            ->orWhere('id', $keyword) : null;

        return ($keyword && $keyword->exists()) ? $keyword->first()?->id : $keyword;
    }
}
