<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Gym\Entities\Reserve;
use Modules\Gym\Http\Repositories\ReserveTemplateRepository;
use Modules\Gym\Http\Repositories\GymRepository;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateBetweenDateRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateIndexRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateShowRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateStoreRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateUpdateRequest;
use Modules\Gym\Entities\ReserveTemplate;
use Illuminate\Support\Facades\Validator;

class ReserveTemplateService
{
    public function __construct(public ReserveTemplateRepository $reserveTemplateRepository, public GymRepository $gymRepository)
    {
    }

    public function index(ReserveTemplateIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $reserveStoreRequest = new ReserveTemplateIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $reserveStoreRequest->rules(),
                    attributes: $reserveStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            $order_by = $fields['order_by'] ?? null;
            $direction_by = $fields['direction_by'] ?? null;

            unset($fields['order_by']);
            unset($fields['direction_by']);

            return $this->reserveTemplateRepository
                ->resolve_paginate(
                    inputs: $fields,
                    orderByColumn: $order_by,
                    directionOrderBy: $direction_by
                );
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ReserveTemplateShowRequest $request, $reserve_template_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->reserveTemplateRepository->withRelations(relations: $withs)->findOrFail($reserve_template_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ReserveTemplateStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $reserveTemplate = $this->reserveTemplateRepository->create($fields);
            DB::commit();
            return $reserveTemplate;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ReserveTemplateUpdateRequest $request, $reserve_template_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var ReserveTemplate $reserveTemplate */
            $reserveTemplate = $this->reserveTemplateRepository->findOrFail($reserve_template_id);

            $this->reserveTemplateRepository->update($reserveTemplate, $fields);
            DB::commit();

            return $this->reserveTemplateRepository->findOrFail($reserve_template_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($reserve_template_id)
    {
        DB::beginTransaction();
        try {
            # find reserveTemplate
            /** @var ReserveTemplate $reserveTemplate */
            $reserveTemplate = $this->reserveTemplateRepository->findOrFail($reserve_template_id);

            # delete reserveTemplate
            $status_delete_reserveTemplate = $this->reserveTemplateRepository->delete($reserveTemplate);

            DB::commit();
            return $status_delete_reserveTemplate;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function betweenDate(ReserveTemplateBetweenDateRequest $request)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $from
             * @var $to
             * @var $gym_id
             */
            extract($fields);

            $from = $from ?? null;
            $to = $to ?? null;
            $gym_id = $gym_id ?? null;

            $reserve_templates = $this->index(['gym_id' => $gym_id]);
            /** @var Reserve $reserves */
            $reserves = Reserve::reserveBetweenDates(gym_id: $gym_id, startDate: $from, endDate: $to);

            $reserve_templates = $reserve_templates->map(function (ReserveTemplate $reserve_template) use ($reserves) {
                $reserve_template['reserve'] = $reserves->filter(function (Reserve $reserve) use ($reserve_template) {
                    Log::info([
                        $reserve->reserve_template_id ===$reserve_template->id ,
                        $reserve->reserve_template_id,
                        $reserve_template->id,
                        gettype($reserve->reserve_template_id),
                        gettype($reserve_template->id)
                    ]);
                    return $reserve->reserve_template_id === $reserve_template->id;
                }) ?? null;
                return $reserve_template;
            });

            return $reserve_templates;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function gender_acceptances(Request $request): array|bool|int|string|null
    {
        $status = $request->status ?? null;
        return ReserveTemplate::getStatusGenderAcceptanceTitle();
    }

    public function statuses(Request $request): array|bool|int|string|null
    {
        $status = $request->status ?? null;
        return ReserveTemplate::getStatusTitle();
    }

}
