<?php

namespace Modules\Payment\Services;

use App\Permissions\RolesEnum;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Payment\Entities\Factor;
use Modules\Payment\Http\Repositories\FactorRepository;
use Modules\Payment\Http\Requests\Factor\FactorIndexRequest;
use Modules\Payment\Http\Requests\Factor\FactorShowRequest;
use Modules\Payment\Http\Requests\Factor\FactorStoreRequest;
use Modules\Payment\Http\Requests\Factor\FactorUpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Http\Requests\Factor\MyFactorRequest;

class FactorService
{
    public function __construct(public FactorRepository $factorRepository)
    {
    }

    public function index(FactorIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $userStoreRequest = new FactorIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $reserve_id
             * @var $reserve_ids
             * @var $status
             * @var $user_id
             * @var $withs
             */
            extract($fields);

            $relations = $withs ?? [];
            $reserve_id = $reserve_id ?? null;
            $reserve_ids = $reserve_ids ?? [];
            if (isset($reserve_id) && filled($reserve_id)) {
                $reserve_ids[] = $reserve_id;
                $reserve_ids = array_unique($reserve_ids);
            }

            unset($fields['reserve_id'], $fields['reserve_ids'], $fields['withs']);

            if (count($reserve_ids)) {
                $relations[] = 'reserves';
                $relations = array_unique($relations);
            }

            /** @var Builder $query */
            $query = $this->factorRepository->queryFull(
                inputs: $fields,
                relations: $relations,
                orderByColumn: $fields['order_by'] ?? 'id',
                directionOrderBy: $fields['direction_by'] ?? 'desc',
            );

            if (count($reserve_ids)) {
                $query = $query->whereHas('reserves', function (Builder $query) use ($reserve_ids) {
                    $query->whereIn('reserves.id', $reserve_ids);
                    // $query->where('reserve_factor.reserve_id', $reserve_id);
                });
            }
            return $this->factorRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function myFactor(MyFactorRequest $request)
    {
        $fields = $request->validated();
        $fields['user_id']=get_user_id_login();
        return $this->index($fields);
    }

    public function show(FactorShowRequest $request, $factor_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $relations = $withs ?? [];
            return $this->factorRepository->withRelations(relations: $relations)->findOrFail($factor_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(FactorStoreRequest|array $request)
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $loginRequest = new FactorStoreRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules(),
                    attributes: $loginRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $reserve_id
             * @var $reserve_ids
             * @var $status
             * @var $user_id
             */
            extract($fields);

            $reserve_id = $reserve_id ?? null;
            $reserve_ids = $reserve_ids ?? [];
            if (isset($reserve_id)) {
                $reserve_ids[] = $reserve_id;
                $reserve_ids = array_unique($reserve_ids);
            }

            unset($fields['reserve_id'], $fields['reserve_ids']);

            // todo check role from set column status.
            if (!user_have_role(roles:RolesEnum::admin->name)) {
                unset($fields['status']);
            }

            /** @var Factor $factor */
            $factor = $this->factorRepository->create($fields);

            // todo should be get from use detach
            $detach = $detach ?? true;
            # sync reserve to factor
            $factor->reserves()->sync($reserve_ids, $detach);

            DB::commit();

            $factor_id = $factor?->id;

            return $this->factorRepository->findOrFail($factor_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(FactorUpdateRequest $request, $factor_id)
    {
        DB::beginTransaction();
        try {

            $fields = $request->validated();

            /**
             * @var $reserve_id
             * @var $reserve_ids
             * @var $reserve_ids
             * @var $status
             * @var $user_id
             */
            extract($fields);

            $reserve_id = $reserve_id ?? null;
            $reserve_ids = $reserve_ids ?? [];
            if (isset($reserve_id)) {
                $reserve_ids[] = $reserve_id;
                $reserve_ids = array_unique($reserve_ids);
            }

            /** @var Factor $reserve */
            $factor = $this->factorRepository->findOrFail($factor_id);

            unset($fields['reserve_id'], $fields['reserve_ids']);
            if (!user_have_role(RolesEnum::admin)) {
                unset($fields['status']);
            }
            $this->factorRepository->update($factor, $fields);

            $detach = $detach ?? true;
            # sync reserve to factor
            $factor->reserves()->sync($reserve_ids, $detach);

            DB::commit();

            $factor_id = $factor?->id;
            return $this->factorRepository->findOrFail($factor_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($factor_id): bool
    {
        DB::beginTransaction();
        try {
            # find Factor
            /** @var Factor $factor */
            $factor = $this->factorRepository->findOrFail($factor_id);

            # delete in pivot table.
            $factor->reserves()->detach();

            # delete
            $this->factorRepository->forceDelete($factor);


            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function listStatusFactor($status = null): array|bool|int|string|null
    {
        return Factor::getStatusTitle($status);
    }
}
