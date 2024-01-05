<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Http\Repositories\ReserveRepository;
use Modules\Gym\Http\Requests\Reserve\ReserveIndexRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveShowRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveStoreBlockRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveStoreRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveUpdateRequest;
use Modules\Gym\Entities\Reserve;
use Modules\Gym\Http\Requests\Reserve\MyReserveRequest;

class ReserveService
{
    public function __construct(public ReserveRepository $reserveRepository)
    {
    }

    public function index(ReserveIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $loginRequest = new ReserveIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules(),
                    attributes: $loginRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            return $this->reserveRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function myReserve(MyReserveRequest $request)
    {
        try {
            $fields = $request->validated();
            $user_id = get_user_id_login();
            $fields['user_id'] = $user_id;
            return $this->index($fields);
            $query = $this->reserveRepository->queryFull(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ReserveShowRequest $request, $reserve_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->reserveRepository->withRelations(relations: $withs)->findOrFail($reserve_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ReserveStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();
            $reserve_template_id = $fields['reserve_template_id'];

            if(!isset($fields['gym_id']) || !filled($fields['gym_id'])){
                /** @var ReserveTemplate $reserveTemplate */
                $reserveTemplate = ReserveTemplate::query()->findOrFail($reserve_template_id);
                $fields['gym_id'] = $reserveTemplate->gym_id;
            }

            $reserve = $this->reserveRepository->create($fields);
            DB::commit();
            return $reserve;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param ReserveStoreBlockRequest $request
     * @return mixed
     * @throws Exception
     */
    public function storeBlocks(ReserveStoreBlockRequest $request): mixed
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $reserve = $this->reserveRepository->create(
                [
                    ...$fields,
                    'user_id' => get_user_id_login(),
                    'status' => Reserve::status_blocked
                ]);

            DB::commit();
            return $reserve;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ReserveUpdateRequest $request, $reserve_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Reserve $reserve */
            $reserve = $this->reserveRepository->findOrFail($reserve_id);

            $this->reserveRepository->update($reserve, $fields);
            DB::commit();

            return $this->reserveRepository->findOrFail($reserve_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($reserve_id)
    {
        DB::beginTransaction();
        try {
            # find reserve
            /** @var Reserve $reserve */
            $reserve = $this->reserveRepository->findOrFail($reserve_id);

            # delete reserve
            $status_delete_reserve = $this->reserveRepository->delete($reserve);

            DB::commit();
            return $status_delete_reserve;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
