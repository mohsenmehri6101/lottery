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

            $reserveTemplates = DB::table('reserve_templates')
                ->where('reserve_templates.gym_id', $gym_id)
                ->leftJoin('reserves', function ($join) use ($to, $from) {
                    $join->on('reserve_templates.id', '=', 'reserves.reserve_template_id')
                        ->whereDate('reserves.dated_at', '>=', $from)
                        ->whereDate('reserves.dated_at', '<=', $to);
                })
                ->select(
                    'reserve_templates.id',
                    'reserve_templates.from',
                    'reserve_templates.to',
                    'reserve_templates.gym_id',
                    'reserve_templates.week_number',
                    'reserve_templates.price',
                    'reserve_templates.gender_acceptance',
                    'reserve_templates.status',
                    'reserves.id as reserve_id',
                    'reserves.status as reserve_status',
                    'reserves.reserve_template_id',
                    'reserves.gym_id as reserve_gym_id',
                    'reserves.user_id',
                    'reserves.payment_status',
                    'reserves.user_creator as reserve_user_creator',
                    'reserves.user_editor as reserve_user_editor',
                    'reserves.dated_at',
                    'reserves.reserved_at',
                    'reserves.reserved_user_id',
                )
                ->get();

            $reserve_templates = [];
            foreach ($reserveTemplates as $template) {
                $data = [
                    'id' => $template->id,
                    'from' => $template->from,
                    'to' => $template->to,
                    'gym_id' => $template->gym_id,
                    'week_number' => $template->week_number,
                    'price' => $template->price,
                    'gender_acceptance' => $template->gender_acceptance,
                    'status' => $template->status,
                    'reserve' => $template->reserve_id ? [
                        'id' => $template->reserve_id,
                        'status' => $template->reserve_status,
                        'reserve_template_id' => $template->reserve_template_id,
                        'gym_id' => $template->reserve_gym_id,
                        'user_id' => $template->user_id,
                        'payment_status' => $template->payment_status,
                        'dated_at' => $template->dated_at,
                        'reserved_at' => $template->reserved_at,
                        'reserved_user_id' => $template->reserved_user_id,
                    ] : null,
                ];

                $reserve_templates[] = $data;
            }
            # see result

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
