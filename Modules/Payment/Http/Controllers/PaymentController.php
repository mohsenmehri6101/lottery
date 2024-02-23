<?php

namespace Modules\Payment\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\Payment\PaymentCreateLinkRequest;
use Modules\Payment\Http\Requests\Payment\PaymentIndexRequest;
use Modules\Payment\Http\Requests\Payment\PaymentShowRequest;
use Modules\Payment\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(public PaymentService $paymentService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/payments",
     *     tags={"payments"},
     *     summary="list payments",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="persian_name",in="query",required=false, @OA\Schema(type="string"),description="persian_name"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(PaymentIndexRequest $request): JsonResponse
    {
        $payments = $this->paymentService->index($request);
        return ResponseHelper::responseSuccess(data: $payments);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/payments/{id}",
     *     tags={"payments"},
     *     summary="show payment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(PaymentShowRequest $request, $payment_id): JsonResponse
    {
        $payment = $this->paymentService->show($request, $payment_id);
        return $payment ? ResponseHelper::responseSuccessShow(data: $payment) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/payments/create-link-payment",
     *     tags={"payments"},
     *     summary="create link payment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="factor_id",in="query",required=true, @OA\Schema(type="integer"),description="factor_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function createLinkPayment(PaymentCreateLinkRequest $request): JsonResponse
    {
        $link = $this->paymentService->createLinkPayment($request);
        return ResponseHelper::responseSuccessShow(data:['link'=>$link],message: 'لینک پرداخت با موفقیت ایجاد شد');
    }
    public function confirmPayment(Request $request): JsonResponse
    {
        $status = $this->paymentService->confirmPayment($request);
        return ResponseHelper::responseSuccessShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/payments/create-link-payment-sadad",
     *     tags={"payments"},
     *     summary="create link payment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="factor_id",in="query",required=true, @OA\Schema(type="integer"),description="factor_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function createLinkPaymentSadad(PaymentCreateLinkRequest $request): JsonResponse
    {
        $link = $this->paymentService->createLinkPaymentSadad($request);
        return ResponseHelper::responseSuccessShow(data:['link'=>$link]);
    }

    public function confirmPaymentSadad(Request $request): JsonResponse
    {
        $status = $this->paymentService->confirmPayment($request);
        return ResponseHelper::responseSuccessShow();
    }

}
