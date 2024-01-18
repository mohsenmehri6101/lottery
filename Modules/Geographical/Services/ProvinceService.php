<?php

namespace Modules\Geographical\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Geographical\Entities\Province;
use Modules\Geographical\Http\Repositories\ProvinceRepository;
use Modules\Geographical\Http\Requests\Province\ProvinceIndexRequest;
use Modules\Geographical\Http\Requests\Province\ProvinceShowRequest;
use Modules\Geographical\Http\Requests\Province\ProvinceStoreRequest;
use Modules\Geographical\Http\Requests\Province\ProvinceUpdateRequest;

class ProvinceService
{
    public function __construct(public ProvinceRepository $provinceRepository)
    {
    }

    public function index(ProvinceIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $provinceIndexRequest = new ProvinceIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $provinceIndexRequest->rules(),
                    attributes: $provinceIndexRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            return $this->provinceRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ProvinceShowRequest $request, $province_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->provinceRepository->withRelations(relations: $withs)->findOrFail($province_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ProvinceStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $province = $this->provinceRepository->create($fields);
            DB::commit();
            return $province;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ProvinceUpdateRequest $request, $province_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Province $province */
            $province = $this->provinceRepository->findOrFail($province_id);

            $this->provinceRepository->update($province, $fields);
            DB::commit();

            return $this->provinceRepository->findOrFail($province_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($province_id)
    {
        DB::beginTransaction();
        try {
            # find province
            /** @var Province $province */
            $province = $this->provinceRepository->findOrFail($province_id);

            # delete province
            $status_delete_province = $this->provinceRepository->delete($province);

            DB::commit();
            return $status_delete_province;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
