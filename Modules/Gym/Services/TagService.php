<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Gym\Http\Requests\Tag\TagIndexRequest;
use Modules\Gym\Http\Requests\Tag\TagShowRequest;
use Modules\Gym\Http\Requests\Tag\TagStoreRequest;
use Modules\Gym\Http\Requests\Tag\TagUpdateRequest;
use Modules\Gym\Entities\Tag;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Http\Repositories\TagRepository;
use Modules\Gym\Http\Requests\Tag\DeleteTagToGymRequest;
use Modules\Gym\Http\Requests\Tag\SyncTagToGymRequest;
use Illuminate\Support\Collection;

class TagService
{
    public function __construct(public TagRepository $tagRepository)
    {
    }

    public function index(TagIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $tagStoreRequest = new TagIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $tagStoreRequest->rules(),
                    attributes: $tagStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            return $this->tagRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(TagShowRequest $request, $tag_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->tagRepository->withRelations(relations: $withs)->findOrFail($tag_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(TagStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $tag = $this->tagRepository->create($fields);
            DB::commit();
            return $tag;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(TagUpdateRequest $request, $tag_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Tag $tag */
            $tag = $this->tagRepository->findOrFail($tag_id);

            $this->tagRepository->update($tag, $fields);
            DB::commit();

            return $this->tagRepository->findOrFail($tag_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($tag_id)
    {
        DB::beginTransaction();
        try {
            # find tag
            /** @var Tag $tag */
            $tag = $this->tagRepository->findOrFail($tag_id);

            # delete tag
            $status_delete_tag = $this->tagRepository->delete($tag);

            DB::commit();
            return $status_delete_tag;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function syncTagToGym(SyncTagToGymRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new SyncTagToGymRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules(),
                    attributes: $loginRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $gym_id
             * @var $detach
             * @var $tag_id
             * @var $tags
             */
            extract($fields);

            $gym_id = $gym_id ?? null;
            $detach = $detach ?? false;
            $tag_id = $tag_id ?? null;
            $tags = $tags ?? [];

            if (isset($tag_id) && $tag_id) {
                $tags[] = $tag_id;
                $tags = array_unique($tags);
            }

            # find gym
            /** @var Gym $gym */
            $gym = Gym::query()->findOrFail($gym_id);

            # sync tag to gym
            $gym->tags()->sync($tags, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteTagToGym(DeleteTagToGymRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $gym_id
             * @var $touch
             * @var $tag_id
             * @var $tags
             */
            extract($fields);

            $gym_id = $gym_id ?? null;
            $touch = $touch ?? true;
            $tag_id = $tag_id ?? null;
            $tags = $tags ?? [];

            if (isset($tag_id) && $tag_id) {
                $tags[] = $tag_id;
                $tags = array_unique($tags);
            }

            # find gym
            /** @var Gym $gym */
            $gym = Gym::query()->findOrFail($gym_id);

            # detach tag to gym
            $gym->tags()->detach($tags, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public static function prepare_tags(...$tags)
    {
        # prepare tags
        if (!is_array($tags) && (is_string($tags) || is_int($tags))) {
            $tags = [$tags];
        }

        if (is_array($tags)) {
            $tags = flatten($tags);
        }

        # standard tags
        /** @var array $tags */
        $tags = (is_string($tags) || is_int($tags)) ? [$tags] : (is_array($tags) ? $tags : []);
        /** @var collection $tags */
        $tags = collect($tags);
        # convert all tags int  and validate
        $tags = $tags->map(function ($tag) {
            $id = self::convertTagToId($tag);
            return $id;
        });
        # filter null and filled
        $tags = $tags->filter(function ($tag) {
            return isset($tag) && filled($tag);
        });
        # unique
        return $tags->unique();
    }

    public static function convertTagToId($tag = null)
    {
        if (is_null($tag)) {
            return $tag;
        }

        if ($tag instanceof Collection) {
            $tag = $tag->toArray() ?? [];
        }

        if ($tag instanceof Tag) {
            return $tag?->id ?? null;
        } elseif (is_array($tag) && isset($tag['id']) && filled($tag['id'])) {
            return $tag['id'] ?? null;
        } elseif (is_numeric($tag)) {
            return $tag;
        }

        /** @var Tag $tag */
        $tag = $tag && filled($tag) ? Tag::query()
            ->where('tag', $tag)
            ->orWhere('id', $tag) : null;

        return ($tag && $tag->exists()) ? $tag->first()?->id : $tag;
    }
}
