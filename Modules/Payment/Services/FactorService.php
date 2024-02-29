<?php

namespace Modules\Payment\Services;

use App\Exceptions\Contracts\ForbiddenCustomException;
use App\Permissions\RolesEnum;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Entities\Reserve;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Payment\Entities\Factor;
use Modules\Payment\Http\Repositories\FactorRepository;
use Modules\Payment\Http\Requests\Factor\FactorIndexRequest;
use Modules\Payment\Http\Requests\Factor\FactorShowRequest;
use Modules\Payment\Http\Requests\Factor\FactorStoreRequest;
use Modules\Payment\Http\Requests\Factor\FactorUpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Http\Requests\Factor\MyFactorRequest;
use Modules\Payment\Http\Requests\Factor\MyGymsFactorRequest;

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
        $fields['user_id'] = get_user_id_login();
        return $this->index($fields);
    }

    public function myGymsFactor(MyGymsFactorRequest $request)
    {
        try {

             if(!is_gym_manager()){
                 throw new ForbiddenCustomException();
             }

            $fields = $request->validated();

            $user_id = get_user_id_login();
            $fields['user_id'] = $user_id;
            $gym_ids = Gym::query()->where('user_gym_manager_id',$user_id)->pluck('id')->toArray();
            $query = $this->factorRepository->queryFull(inputs: $fields);
            $query = $this->factorRepository->byArray($query,'gym_id',$gym_ids);

            return $this->factorRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
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

    public static function setPriceForFactor(Factor $factor): void
    {
        $totalPrice = 0;

        /** @var Reserve $reserve */
        foreach ($factor->reserves as $reserve) {
            /** @var ReserveTemplate $reserveTemplate */
            $reserveTemplate = $reserve->reserveTemplate;
            /** @var Gym $gym */
            $gym = $reserve->gym;
            $price = $reserveTemplate->price ?? $gym->price;

            if ($reserveTemplate->discount > 0 && $reserveTemplate->discount <= 100) {
                $discountedPrice = $price * (1 - $reserveTemplate->discount / 100);
                $price = $discountedPrice;
            }

            if ($reserve->want_ball && $reserveTemplate->is_ball) {
                $price += $gym->ball_price;
            }

            // Update the price for the reserve
            $factor->reserves()->updateExistingPivot(
                $reserve->id,
                ['price' => $price]
            );

            $totalPrice += $price;
        }
        # Update the total price for the factor
        $factor->update(['total_price' => $totalPrice]);
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
             * @var $gym_id
             */
            extract($fields);

            $reserve_id = $reserve_id ?? null;
            $reserve_ids = $reserve_ids ?? [];
            if (isset($reserve_id)) {
                $reserve_ids[] = $reserve_id;
                $reserve_ids = array_unique($reserve_ids);
            }

            unset($fields['reserve_id'], $fields['reserve_ids']);

            # If gym_id is not set in the input fields, get it from ReserveTemplate
            if (!isset($fields['gym_id']) || !filled($fields['gym_id'])) {
                $reserve_id_first = $reserve_ids[0];
                /** @var Reserve $reserveFirst */
                $reserveFirst = Reserve::query()->findOrFail($reserve_id_first);
                $fields['gym_id'] = $reserveFirst->gym_id ?? null;
                if (!isset($fields['user_id']) || !filled($fields['user_id'])) {
                    $fields['user_id'] = $user_id= $user_id ?? $reserveFirst->user_id;
                }
            }

            if (!isset($fields['user_id']) || !filled($fields['user_id'])) {
                $fields['user_id'] = get_user_id_login() ?? null;
            }

            # todo check role from set column status.
            if (!user_have_role(roles: RolesEnum::admin->name)) {
                unset($fields['status']);
            }

            /** @var Factor $factor */
            $factor = $this->factorRepository->create($fields);

            # todo should be get from use detach
            $detach = $detach ?? true;

            # sync reserve to factor
            $factor->reserves()->sync($reserve_ids, $detach);

            self::setPriceForFactor($factor);

            DB::commit();

            $factor_id = $factor?->id;

            # todo after return factor, set description fields.
            $factor->description = Factor::calculateDescription($factor);

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

            $factor->reserves()->sync($reserve_ids, $detach);

            self::setPriceForFactor($factor);

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
