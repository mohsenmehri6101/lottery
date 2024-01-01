<?php

namespace Modules\Authentication\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class WebInfoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/authentication/web-info",
     *     tags={"web-info"},
     *     summary="لیست کاربران",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function webInfo(): JsonResponse
    {
        $configs = config('configs.web_info');
        return ResponseHelper::responseSuccess($configs);
    }
}
