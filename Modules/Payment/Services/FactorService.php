<?php

namespace Modules\Payment\Services;

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
    public static function calculateDescription(Factor $factor): string
    {
        $description = "فاکتور مربوط به ";
        // Load associated reserves with their templates and gyms
        $reserves = $factor->reserves()->with('reserveTemplate.gym', 'user')->get();
        // Build description
        foreach ($reserves as $reserve) {
            $gymName = $reserve->reserveTemplate->gym->name;
            $reserveId = $reserve->id;
            $reserveDate = $reserve->dated_at->format('Y-m-d');
            $discount = $reserve->reserveTemplate->discount;
            $ballStatus = $reserve->want_ball ? 'بله' : 'خیر';
            $ballPrice = $reserve->reserveTemplate->gym->ball_price;

            // Check if user information is available
            if ($reserve->user) {
                // Check if name and family are set
                if ($reserve->user->name && $reserve->user->family) {
                    $userInfo = "({$reserve->user->name} {$reserve->user->family}, {$reserve->user->mobile})";
                } else {
                    $userInfo = "({$reserve->user->mobile})";
                }
            } else {
                $userInfo = '';
            }

            $description .= "{$gymName}{$userInfo} (شناسه رزرو: {$reserveId}, تاریخ: {$reserveDate}, تخفیف: {$discount}%, توپ: {$ballStatus}, قیمت توپ: {$ballPrice}), ";
        }
        // Add total price to the description
        $description .= "با مجموع قیمت: {$factor->total_price}";
        return $description;
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
            if (!user_have_role(roles: RolesEnum::admin->name)) {
                unset($fields['status']);
            }

            /** @var Factor $factor */
            $factor = $this->factorRepository->create($fields);

            // todo should be get from use detach
            $detach = $detach ?? true;
            # sync reserve to factor
            $factor->reserves()->sync($reserve_ids, $detach);

            self::calculatePriceForFactor($factor);

            DB::commit();

            $factor_id = $factor?->id;

            // todo after return factor, set description fields

            $factor->description =self::calculateDescription($factor);

            return $this->factorRepository->findOrFail($factor_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public static function calculatePriceForFactor(Factor $factor): void
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

        // Update the total price for the factor
        $factor->update(['total_price' => $totalPrice]);
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

            self::calculatePriceForFactor($factor);

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
