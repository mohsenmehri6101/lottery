<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Http\Repositories\ReserveTemplateRepository;
use Modules\Gym\Http\Repositories\GymRepository;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateBetweenDateRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateIndexRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateMultipleStoreRequest;
use Modules\Gym\Http\Requests\ReserveTemplate\ReserveTemplateMultipleUpdateRequest;
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
                $reserveIndexRequest = new ReserveTemplateIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $reserveIndexRequest->rules(),
                    attributes: $reserveIndexRequest->attributes(),
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

    public function store(ReserveTemplateStoreRequest|array $request)
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $reserveTemplateStoreRequest = new ReserveTemplateStoreRequest();
                $fields = Validator::make(data: $request,
                    rules: $reserveTemplateStoreRequest->rules(),
                    attributes: $reserveTemplateStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            $reserveTemplate = $this->reserveTemplateRepository->create($fields);

            DB::commit();
            return $reserveTemplate;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function multipleStore(ReserveTemplateMultipleStoreRequest $request): array
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();
            $reserve_templates = $fields['reserve_templates'];
            $result = [];

            if(count($reserve_templates)){
                foreach($reserve_templates as $reserve_template){
                    $result[]=$this->store($reserve_template);
                }
            }

            DB::commit();
            return $result;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ReserveTemplateUpdateRequest|array $request, $reserve_template_id)
    {
        DB::beginTransaction();
        try {
            if (is_array($request)) {
                $reserveTemplateUpdateRequest = new ReserveTemplateUpdateRequest();
                $fields = Validator::make(data: $request,
                    rules: $reserveTemplateUpdateRequest->rules(),
                    attributes: $reserveTemplateUpdateRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

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

    public function multipleUpdate(ReserveTemplateMultipleUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $reserve_templates= $fields['reserve_templates'];

            if(count($reserve_templates)){
                foreach($reserve_templates as $reserve_template){
                    $reserve_template_id=$reserve_template['id'] ?? null;
                    unset($reserve_template['id']);
                    $this->update($reserve_template,$reserve_template_id);
                }
            }

            DB::commit();
            return [];
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

    public function betweenDate(ReserveTemplateBetweenDateRequest $request): array
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
                    'reserve_templates.discount',
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
                    'price' => $template->price ?? $gym->price,
                    # 'price' => $template->price,
                    'discount' => $template->discount,
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

            # select gym
            /** @var Gym $gym */
            $gym = Gym::query()->with(['urlImages','sports','attributes'])->find($gym_id);
            # select gym

            return ['gym'=>$gym,'reserve_templates'=>$reserve_templates];

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function gender_acceptances(Request|array $request): array|bool|int|string|null
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
