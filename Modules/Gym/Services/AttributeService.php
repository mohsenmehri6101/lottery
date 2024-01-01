<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Gym\Http\Requests\Attribute\AttributeIndexRequest;
use Modules\Gym\Http\Requests\Attribute\AttributeShowRequest;
use Modules\Gym\Http\Requests\Attribute\AttributeStoreRequest;
use Modules\Gym\Http\Requests\Attribute\AttributeUpdateRequest;
use Modules\Gym\Entities\Attribute;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Http\Repositories\AttributeRepository;
use Modules\Gym\Http\Requests\Attribute\DeleteAttributeToGymRequest;
use Modules\Gym\Http\Requests\Attribute\SyncAttributeToGymRequest;

class AttributeService
{
    public function __construct(public AttributeRepository $attributeRepository)
    {
    }

    public function index(AttributeIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
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

    public function syncAttributeToGym(SyncAttributeToGymRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new SyncAttributeToGymRequest();
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
             * @var $attribute_id
             * @var $attributes
             */
            extract($fields);

            $gym_id = $gym_id ?? null;
            $detach = $detach ?? false;
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];

            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }

            # find gym
            /** @var Gym $gym */
            $gym = Gym::query()->findOrFail($gym_id);

            # sync attribute to gym
            $gym->attributes()->sync($attributes, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteAttributeToGym(DeleteAttributeToGymRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $gym_id
             * @var $touch
             * @var $attribute_id
             * @var $attributes
             */
            extract($fields);

            $gym_id = $gym_id ?? null;
            $touch = $touch ?? true;
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];

            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }

            # find gym
            /** @var Gym $gym */
            $gym = $this->attributeRepository->findOrFail($gym_id);

            # detach attribute to gym
            $gym->attributes()->detach($attributes, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
