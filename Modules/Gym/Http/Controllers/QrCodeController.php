<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\QrCodeIndexRequest;
use Modules\Gym\Http\Requests\QrCodeShowRequest;
use Modules\Gym\Http\Requests\QrCodeStoreRequest;
use Modules\Gym\Http\Requests\QrCodeUpdateRequest;
use Modules\Gym\Services\QrCodeService;

class QrCodeController extends Controller
{
    public function __construct(public QrCodeService $qrCodeService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/qr-codes",
     *     tags={"qr-codes"},
     *     summary="List QR codes",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function index(QrCodeIndexRequest $request): JsonResponse
    {
        $qrCodes = $this->qrCodeService->index($request);
        return ResponseHelper::responseSuccess(data: $qrCodes);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/qr-codes/{id}",
     *     tags={"qr-codes"},
     *     summary="Show a QR code",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function show(QrCodeShowRequest $request, $qrCode_id): JsonResponse
    {
        $qrCode = $this->qrCodeService->show($request, $qrCode_id);
        return $qrCode ? ResponseHelper::responseSuccessShow(data: $qrCode) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/qr-codes",
     *     tags={"qr-codes"},
     *     summary="Create a QR code",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function store(QrCodeStoreRequest $request): JsonResponse
    {
        $qrCode = $this->qrCodeService->store($request);
        return $qrCode ? ResponseHelper::responseSuccessStore(data: $qrCode) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/qr-codes/{id}",
     *     tags={"qr-codes"},
     *     summary="Update a QR code",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent()),
     *     @OA\Response(response=422, description="Unprocessable Entity", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function update(QrCodeUpdateRequest $request, $qrCode_id): JsonResponse
    {
        $qrCode = $this->qrCodeService->update($request, $qrCode_id);
        return $qrCode ? ResponseHelper::responseSuccessUpdate(data: $qrCode) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/qr-codes/{id}",
     *     tags={"qr-codes"},
     *     summary="Delete a QR code",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function destroy($qrCode_id): JsonResponse
    {
        $status_delete = $this->qrCodeService->destroy($qrCode_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

}
