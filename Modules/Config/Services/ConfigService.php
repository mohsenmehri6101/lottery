<?php

namespace Modules\Config\Services;

use Illuminate\Support\Facades\DB;
use Modules\Config\Entities\Config;
use Modules\Config\Http\Repositories\ConfigRepository;
use Modules\Config\Http\Requests\Config\ConfigIndexRequest;
use Modules\Config\Http\Requests\Config\ConfigShowRequest;
use Modules\Config\Http\Requests\Config\ConfigStoreRequest;
use Modules\Config\Http\Requests\Config\ConfigUpdateRequest;
use Exception;

class ConfigService
{
    public function __construct(public ConfigRepository $configRepository)
    {
    }

    public function index(ConfigIndexRequest $request)
    {
        try {
            $fields = $request->validated();
            return $this->configRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ConfigShowRequest $request, $config_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $withs = $withs ?? [];

            return $this->configRepository->withRelations(relations: $withs)->findOrFail($config_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ConfigStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $config = $this->configRepository->create($fields);

            DB::commit();
            return $config;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ConfigUpdateRequest $request, $config_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            # find config
            /** @var Config $config */
            $config = $this->configRepository->findOrFail($config_id);

            # update config
            $this->configRepository->update($config, $fields);

            DB::commit();

            return $this->configRepository->findOrFail($config_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($config_id): bool
    {
        DB::beginTransaction();
        try {
            # find Config
            /** @var Config $config */
            $config = $this->configRepository->findOrFail($config_id);

            # delete config
            $this->configRepository->delete($config);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public static function firstOrCreate($key = null, $value = null, $title = null, $tag = null)
    {
        /** @var ConfigRepository $configRepository */
        $configRepository = resolve('ConfigRepository');
        if (!is_null($key)) {
            $fields = [
                'key' => $key,
                'value' => $value,
                'title' => $title,
                'tag' => $tag,
            ];
            $attributes = [
                'key' => $key,
            ];
            $config_in_db = $configRepository->firstOrCreate($attributes, $fields);
            return $config_in_db ?? null;
        }
        return null;
    }
}
