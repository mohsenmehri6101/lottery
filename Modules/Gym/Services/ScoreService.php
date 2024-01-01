<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Http\Requests\Score\ScoreIndexRequest;
use Modules\Gym\Http\Requests\Score\ScoreShowRequest;
use Modules\Gym\Http\Requests\Score\ScoreStoreRequest;
use Modules\Gym\Http\Requests\Score\ScoreUpdateRequest;
use Modules\Gym\Entities\Score;
use Modules\Gym\Http\Repositories\ScoreRepository;

class ScoreService
{
    public function __construct(public ScoreRepository $scoreRepository)
    {
    }

    public function index(ScoreIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
            return $this->scoreRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ScoreShowRequest $request, $score_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->scoreRepository->withRelations(relations: $withs)->findOrFail($score_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ScoreStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $score
             * @var $gym_id
             */
            extract($fields);

            # $score = $score ?? null;
            $gym_id = $gym_id ?? null;

            $fields_query = ['gym_id' => $gym_id];

            if ($user_id = get_user_id_login()) {
                $fields_query['user_id'] = $user_id;
            } else {
                $fields_query['ip'] = request()->ip() ?? null;
                $fields_query['user_agent'] = request()->userAgent() ?? null;
            }

            $score = $this->scoreRepository->updateOrCreate(attributes: $fields_query, values: $fields);

            # calculate average score gym
            GymService::updateScore($gym_id);

            DB::commit();
            return $score;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ScoreUpdateRequest $request, $score_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Score $score */
            $score = $this->scoreRepository->findOrFail($score_id);

            $this->scoreRepository->update($score, $fields);

            # calculate average score gym
            GymService::updateScore($score->gym_id);

            DB::commit();

            return $this->scoreRepository->findOrFail($score_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($score_id)
    {
        DB::beginTransaction();
        try {
            # find score
            /** @var Score $score */
            $score = $this->scoreRepository->findOrFail($score_id);

            # delete score
            $status_delete_score = $this->scoreRepository->delete($score);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
