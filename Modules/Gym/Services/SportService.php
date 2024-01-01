<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Gym\Http\Requests\Sport\SportIndexRequest;
use Modules\Gym\Http\Requests\Sport\SportShowRequest;
use Modules\Gym\Http\Requests\Sport\SportStoreRequest;
use Modules\Gym\Http\Requests\Sport\SportUpdateRequest;
use Modules\Gym\Entities\Sport;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Http\Repositories\SportRepository;
use Modules\Gym\Http\Requests\Sport\DeleteSportToGymRequest;
use Modules\Gym\Http\Requests\Sport\SyncSportToGymRequest;

class SportService
{
    public function __construct(public SportRepository $sportRepository)
    {
    }

    public function index(SportIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
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

    public function syncSportToGym(SyncSportToGymRequest|array $request): bool
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new SyncSportToGymRequest();
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
             * @var $sport_id
             * @var $sports
             */
            extract($fields);

            $gym_id = $gym_id ?? null;
            $detach = $detach ?? false;
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];

            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }

            # find gym
            /** @var Gym $gym */
            $gym = Gym::query()->findOrFail($gym_id);

            # sync sport to gym
            $gym->sports()->sync($sports, $detach);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteSportToGym(DeleteSportToGymRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $gym_id
             * @var $touch
             * @var $sport_id
             * @var $sports
             */
            extract($fields);

            $gym_id = $gym_id ?? null;
            $touch = $touch ?? true;
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];

            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }

            # find gym
            /** @var Gym $gym */
            $gym = $this->sportRepository->findOrFail($gym_id);

            # detach sport to gym
            $gym->sports()->detach($sports, $touch);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
