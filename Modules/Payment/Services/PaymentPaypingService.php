<?php

namespace Modules\Payment\Services;

use App\Exceptions\Contracts\CreateLinkPaymentException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Exception;

class PaymentPaypingService
{
    private string $TokenCode;
    private static string $PAYMENT_URL = 'https://api.payping.ir/v1/';
    private static string $PAYMENT_ENDPOINT = 'pay';
    private static string $VERIFY_ENDPOINT = 'pay/verify';
    public function __construct(string|null $token =null)
    {
        $this->TokenCode = $token ?? env('PAYMENT_PAYPING_TOKEN');
    }
    public function createLinkPayment($clientRefId, $mobile, $amount, $description, $returnUrl, $payerName): string
    {
        try {
            $data = [
                'clientRefId' => $clientRefId, /* شماره فاکتور */
                'payerIdentity' => $mobile, /* شماره موبایل یا ایمیل پرداخت کننده */
                'payerName' => $payerName, /* نام کاربر پرداخت کننده */
                'amount' => is_string($amount) ? floatval($amount) : $amount, /* required *//* مبلغ تراکنش */
                'Description' => $description, /* توضیحات */
                'returnUrl' => $returnUrl, /* required *//* آدرس برگشتی از سمت درگاه */
            ];

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->TokenCode,
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'curl' => [CURLOPT_SSL_VERIFYPEER => false],
            ])->post(self::$PAYMENT_URL . self::$PAYMENT_ENDPOINT, $data);

            $statusCode = $response->status();
            $responseData = $response->json();
            $code = $responseData['code'] ?? null;

            if($statusCode === Response::HTTP_OK && $responseData['code']){
                return  self::$PAYMENT_URL . 'pay/gotoipg/' . $code;
            }

            throw new CreateLinkPaymentException(/*extra_data:[$response->json()]*/);

        } catch (Exception $exception) {
            throw new $exception;
        }
    }
    public function confirmPayment($authority, $amount, $factor_id): bool
    {
        try {
            $data = [
                'merchant_id' => $this->TokenCode,
                'amount' => $amount,
                'authority' => $authority,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post(self::$PAYMENT_URL . 'verify.json', $data);

            $statusCode = $response->status();
            $responseData = $response->json();

            if ($statusCode === 200 && isset($responseData['data']['code']) && $responseData['data']['code'] === 100) {
                if ($factor_id == $responseData['data']['ref_id'] && $amount == $responseData['data']['amount']) {
                    return true;
                } else {
                    throw new Exception('Factor_id or amount mismatch in payment verification');
                }
            }
            throw new Exception('Payment verification failed');

        } catch (Exception $exception) {
            throw $exception;
        }
    }
    /*public function confirmPayment($refid, $amount): bool
    {
        $data = [
            'amount' => $amount,
            'refId' => $refid
        ];

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->TokenCode,
                'cache-control' => 'no-cache',
                'content-type' => 'application/json'
            ])->post(self::$PAYMENT_URL . self::$VERIFY_ENDPOINT, $data);

            $statusCode = $response->status();
            return $statusCode === Response::HTTP_OK;

        }
     catch (Exception $exception) {
            throw new $exception;
        }
    }*/
}
