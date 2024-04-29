<?php

namespace Modules\Article\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Exception;

class SeederController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/seeders/seeder-fake-all-data",
     *     tags={"seeders"},
     *     summary="Seed fake data for Article module",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function seederFakeAllData(): JsonResponse
    {
        try {
            $app_env = env('APP_ENV', 'production');
            if ($app_env === 'production') {
                throw new Exception('in project production you not allow do that');
            }
            Artisan::call('module:seed', ['module' => 'Article']);

            $message = 'Article module seeded successfully';
            return ResponseHelper::responseSuccess( message: $message);
        } catch (Exception $e) {
            report($e);
            return ResponseHelper::responseFailed();
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/seeders/migrate-refresh-and-seeder-fake-all-data",
     *     tags={"seeders"},
     *     summary="Seed fake data for Article module",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function migrateRefreshAndSeederFakeAllData(): JsonResponse
    {
        $app_env = env('APP_ENV', 'production');
        if ($app_env === 'production') {
            throw new Exception('in project production you not allow do that');
        }
        Artisan::call('migrate:refresh');

        return $this->seederFakeAllData();
    }

}
