<?php

namespace Modules\Article\Services;

use App\Exceptions\Contracts\ForbiddenCustomException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Article\Entities\Article;
use Modules\Article\Entities\ReserveTemplate;
use Modules\Article\Http\Repositories\ReserveRepository;
use Modules\Article\Http\Requests\Reserve\MyArticleReserveRequest;
use Modules\Article\Http\Requests\Reserve\ReserveBetweenDateRequest;
use Modules\Article\Http\Requests\Reserve\ReserveIndexRequest;
use Modules\Article\Http\Requests\Reserve\ReserveShowRequest;
use Modules\Article\Http\Requests\Reserve\ReserveStoreFactorLinkPaymentRequest;
use Modules\Article\Http\Requests\Reserve\ReserveStoreBlockRequest;
use Modules\Article\Http\Requests\Reserve\ReserveStoreRequest;
use Modules\Article\Http\Requests\Reserve\ReserveUpdateRequest;
use Modules\Article\Entities\Reserve;
use Modules\Article\Http\Requests\Reserve\MyReserveRequest;
use Modules\Payment\Entities\Factor;
use Modules\Payment\Services\FactorService;
use Modules\Payment\Services\PaymentService;

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
            // todo check what is wrong ?
            $query = $this->reserveRepository->queryFull(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function myArticleReserve(MyArticleReserveRequest $request)
    {
        try {
            if (is_article_manager()) {
                throw new ForbiddenCustomException();
            }

            $fields = $request->validated();
            #################################
            $user_id = get_user_id_login();
            $fields['user_id'] = $user_id;
            $article_ids = Article::query()->where('user_article_manager_id', $user_id)->pluck('id')->toArray();
            $query = $this->reserveRepository->queryFull(inputs: $fields);
            $query = $this->reserveRepository->byArray($query, 'article_id', $article_ids);
            #################################
            return $this->reserveRepository->resolve_paginate(query: $query);

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
            return $this->reserveRepository->withRelations(relations: $withs)
                ->where('id', $reserve_id)
                ->orWhere('tracking_code', $reserve_id)
                ->first();

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
            if (!isset($fields['article_id']) || !filled($fields['article_id'])) {
                /** @var ReserveTemplate $reserveTemplate */
                $reserveTemplate = ReserveTemplate::query()->findOrFail($reserve_template_id);
                $fields['article_id'] = $reserveTemplate->article_id;
            }

            $reserve = $this->reserveRepository->create($fields);
            DB::commit();
            return $reserve;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function storeAndPrintFactorAndCreateLinkPayment(ReserveStoreFactorLinkPaymentRequest $request): ?array
    {
        DB::beginTransaction();
        try {

            $fields = $request->validated();

            /**
             * @var $reserves
             */
            extract($fields);

            $reserves = collect($reserves);

            $reserveIds = [];
            $article_id = null;

            # save reserves
            $reserves->each(function ($reserve) use (&$reserveIds) {
                $reserve_template_id = $reserve['reserve_template_id'];
                if (!isset($reserve['article_id']) || !filled($reserve['article_id'])) {
                    /** @var ReserveTemplate $reserveTemplate */
                    $reserveTemplate = ReserveTemplate::query()->findOrFail($reserve_template_id);
                    $article_id = $reserve['article_id'] = $reserveTemplate->article_id;
                }
                # user_id
                if (!isset($reserve['user_id']) || !filled($reserve['user_id'])) {
                    $reserve['user_id'] = get_user_id_login();
                }
                /** @var Reserve $reserveModel */
                $reserveModel = $this->reserveRepository->create($reserve);
                $reserveIds[] = $reserveModel->id;
            });

            # save factor
            /** @var FactorService $factorService */
            $factorService = resolve('FactorService');

            /** @var Factor $factor */
            $factor = $factorService->store([
                'reserve_ids' => $reserveIds,
                'user_id' => get_user_id_login(),
                'article_id' => $article_id,
            ]);

            $factor->update([
                'description' => Factor::calculateDescription($factor),
                'total_price' => Factor::calculatePriceForFactor($factor)
            ]);

            # create link payment
            /** @var PaymentService $paymentService */
            $paymentService = resolve('PaymentService');
            $url = $paymentService->createLinkPayment(['factor_id' => $factor->id]);

            if (filled($url)) {
                /** @var Factor $factor */
                $factor->reserves()->update(['status' => Reserve::status_reserving]);
            }

            DB::commit();

            return ['url' => $url, 'factor' => $factor->with('reserves')->first()->toArray(), 'test' => true];
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

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

    public function reserveBetweenDates(ReserveBetweenDateRequest $request): Collection|array
    {
        try {
            $fields = $request->validated();

            /**
             * @var $from
             * @var $to
             * @var $article_id
             * @var $order_by
             * @var $direction_by
             */
            extract($fields);
            // todo should be check different between from and two less one month.
            return Reserve::reserveBetweenDates(article_id: $article_id, startDate: $from, endDate: $to);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function statuses(Request $request): array|bool|int|string|null
    {
        return Reserve::getStatusTitle();
    }
}
