<?php

namespace Modules\Geographical\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Geographical\Entities\City;
use Modules\Geographical\Http\Repositories\CityRepository;
use Modules\Geographical\Http\Requests\City\CityIndexRequest;
use Modules\Geographical\Http\Requests\City\CityShowRequest;
use Modules\Geographical\Http\Requests\City\CityStoreRequest;
use Modules\Geographical\Http\Requests\City\CityUpdateRequest;
use Illuminate\Support\Facades\Validator;

class CityService
{
    public function __construct(public CityRepository $cityRepository)
    {
    }
    public function index(CityIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $cityStoreRequest = new CityIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $cityStoreRequest->rules(),
                    attributes: $cityStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }


            return $this->cityRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(CityShowRequest $request, $city_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->cityRepository->withRelations(relations: $withs)->findOrFail($city_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(CityStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $city = $this->cityRepository->create($fields);
            DB::commit();
            return $city;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(CityUpdateRequest $request, $city_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var City $city */
            $city = $this->cityRepository->findOrFail($city_id);

            $this->cityRepository->update($city, $fields);
            DB::commit();

            return $this->cityRepository->findOrFail($city_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($city_id)
    {
        DB::beginTransaction();
        try {
            # find city
            /** @var City $city */
            $city = $this->cityRepository->findOrFail($city_id);

            # delete city
            $status_delete_city = $this->cityRepository->delete($city);

            DB::commit();
            return $status_delete_city;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
