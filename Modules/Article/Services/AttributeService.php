<?php

namespace Modules\Article\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Article\Http\Requests\Attribute\AttributeIndexRequest;
use Modules\Article\Http\Requests\Attribute\AttributeShowRequest;
use Modules\Article\Http\Requests\Attribute\AttributeStoreRequest;
use Modules\Article\Http\Requests\Attribute\AttributeUpdateRequest;
use Modules\Article\Entities\Attribute;
use Modules\Article\Entities\Article;
use Modules\Article\Http\Repositories\AttributeRepository;
use Modules\Article\Http\Requests\Attribute\DeleteAttributeToArticleRequest;
use Modules\Article\Http\Requests\Attribute\SyncAttributeToArticleRequest;

class AttributeService
{
    public function __construct(public AttributeRepository $attributeRepository)
    {
    }

    public function index(AttributeIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $attributeStoreRequest = new AttributeIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $attributeStoreRequest->rules(),
                    attributes: $attributeStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            return $this->attributeRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(AttributeShowRequest $request, $attribute_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->attributeRepository->withRelations(relations: $withs)->findOrFail($attribute_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(AttributeStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $attribute = $this->attributeRepository->create($fields);
            DB::commit();
            return $attribute;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(AttributeUpdateRequest $request, $attribute_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Attribute $attribute */
            $attribute = $this->attributeRepository->findOrFail($attribute_id);

            $this->attributeRepository->update($attribute, $fields);
            DB::commit();

            return $this->attributeRepository->findOrFail($attribute_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($attribute_id)
    {
        DB::beginTransaction();
        try {
            # find attribute
            /** @var Attribute $attribute */
            $attribute = $this->attributeRepository->findOrFail($attribute_id);

            # delete attribute
            $status_delete_attribute = $this->attributeRepository->delete($attribute);

            DB::commit();
            return $status_delete_attribute;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncAttributeToArticle(SyncAttributeToArticleRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new SyncAttributeToArticleRequest();
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
             * @var $attribute_id
             * @var $attributes
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $detach = $detach ?? false;
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];

            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }

            # find article
            /** @var Article $article */
            $article = Article::query()->findOrFail($article_id);

            # sync attribute to article
            $article->attributes()->sync($attributes, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteAttributeToArticle(DeleteAttributeToArticleRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $article_id
             * @var $touch
             * @var $attribute_id
             * @var $attributes
             */
            extract($fields);

            $article_id = $article_id ?? null;
            $touch = $touch ?? true;
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];

            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }

            # find article
            /** @var Article $article */
            $article = $this->attributeRepository->findOrFail($article_id);

            # detach attribute to article
            $article->attributes()->detach($attributes, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
