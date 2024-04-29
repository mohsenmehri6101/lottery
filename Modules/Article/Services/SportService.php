<?php

namespace Modules\Article\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Article\Http\Requests\Sport\SportIndexRequest;
use Modules\Article\Http\Requests\Sport\SportShowRequest;
use Modules\Article\Http\Requests\Sport\SportStoreRequest;
use Modules\Article\Http\Requests\Sport\SportUpdateRequest;
use Modules\Article\Entities\Sport;
use Modules\Article\Entities\Article;
use Modules\Article\Http\Repositories\SportRepository;
use Modules\Article\Http\Requests\Sport\DeleteSportToArticleRequest;
use Modules\Article\Http\Requests\Sport\SyncSportToArticleRequest;

class SportService
{
    public function __construct(public SportRepository $sportRepository)
    {
    }

    public function index(SportIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $sportStoreRequest = new SportIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $sportStoreRequest->rules(),
                    attributes: $sportStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }
            return $this->sportRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(SportShowRequest $request, $sport_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->sportRepository->withRelations(relations: $withs)->findOrFail($sport_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(SportStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $sport = $this->sportRepository->create($fields);
            DB::commit();
            return $sport;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(SportUpdateRequest $request, $sport_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Sport $sport */
            $sport = $this->sportRepository->findOrFail($sport_id);

            $this->sportRepository->update($sport, $fields);
            DB::commit();

            return $this->sportRepository->findOrFail($sport_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($sport_id)
    {
        DB::beginTransaction();
        try {
            # find sport
            /** @var Sport $sport */
            $sport = $this->sportRepository->findOrFail($sport_id);

            # delete sport
            $status_delete_sport = $this->sportRepository->delete($sport);

            DB::commit();
            return $status_delete_sport;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncSportToArticle(SyncSportToArticleRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new SyncSportToArticleRequest();
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
             * @var $sport_id
             * @var $sports
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $detach = $detach ?? false;
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];

            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }

            # find article
            /** @var Article $article */
            $article = Article::query()->findOrFail($article_id);

            # sync sport to article
            $article->sports()->sync($sports, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteSportToArticle(DeleteSportToArticleRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $article_id
             * @var $touch
             * @var $sport_id
             * @var $sports
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $touch = $touch ?? true;
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];

            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }

            # find article
            /** @var Article $article */
            $article = $this->sportRepository->findOrFail($article_id);

            # detach sport to article
            $article->sports()->detach($sports, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
