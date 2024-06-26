<?php

namespace Modules\Payment\Services;

use App\Exceptions\Contracts\CreateLinkPaymentException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Exception;

class PaymentZarinpalService
{
    private string $token_code;
    private static string $PAYMENT_URL;
    public function __construct(string|null $token =null)
    {
        $this->token_code = $token ?? config('configs.payment.zarinpal.merchant_id');
        self::$PAYMENT_URL = config('configs.payment.zarinpal.payment_url');
    }
    public function createLinkPayment($amount, $description, $callbackUrl, $mobile = null, $email = null, $factor_id = null): string
    {
        try {
            $data = [
                'merchant_id' => $this->token_code,
                'amount' => $amount,
                'description' => $description,
                'callback_url' => $callbackUrl,
                'metadata' => [
                    'mobile' => $mobile,
                    'email' => $email,
                    'factor_id' => $factor_id,
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post(self::$PAYMENT_URL . 'request.json', $data);

            $status_code = $response->status();
            $response_data = $response->json();

            if ($status_code === Response::HTTP_OK && isset($response_data['data']['authority'])) {
                return 'https://www.zarinpal.com/pg/StartPay/' . $response_data['data']['authority'];
            }

            throw new CreateLinkPaymentException(/*extra_data:[$response_data]*/);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function confirmPayment($authority, $amount)
    {
        try {
            $data = [
                'merchant_id' => $this->token_code,
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
                // Payment is verified successfully
                return $responseData['data'];
            }

            // Handle other cases or throw an exception
            throw new Exception('Payment verification failed');

        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
