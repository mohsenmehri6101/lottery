<?php

namespace Modules\Config\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Config\Http\Requests\Config\ConfigIndexRequest;
use Modules\Config\Http\Requests\Config\ConfigShowRequest;
use Modules\Config\Http\Requests\Config\ConfigStoreRequest;
use Modules\Config\Http\Requests\Config\ConfigUpdateRequest;
use Modules\Config\Services\ConfigService;

class ConfigController extends Controller
{
    public function __construct(public ConfigService $configService)
    {
    }

    public function index(ConfigIndexRequest $request): JsonResponse
    {
        $configs = $this->configService->index($request);
        return ResponseHelper::responseSuccess(data:$configs);
    }

    public function show(ConfigShowRequest $request,$config_id): JsonResponse
    {
        $config = $this->configService->show($request,$config_id);
        return $config ? ResponseHelper::responseSuccessShow(data:$config) : ResponseHelper::responseFailedShow();
    }

    public function store(ConfigStoreRequest $request): JsonResponse
    {
        $config = $this->configService->store($request);
        return $config ? ResponseHelper::responseSuccessStore(data:$config) : ResponseHelper::responseFailedStore();
    }

    public function update(ConfigUpdateRequest $request, $config_id): JsonResponse
    {
        $config = $this->configService->update($request, $config_id);
        return $config ? ResponseHelper::responseSuccessUpdate(data:$config) : ResponseHelper::responseFailedUpdate();
    }

    public function destroy($config_id): JsonResponse
    {
        $status_delete = $this->configService->destroy($config_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
