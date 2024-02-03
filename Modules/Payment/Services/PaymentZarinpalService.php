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

    /**
     * @throws CreateLinkPaymentException
     */
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

            $statusCode = $response->status();
            $responseData = $response->json();

            if ($statusCode === Response::HTTP_OK && isset($responseData['data']['authority'])) {
                return 'https://www.zarinpal.com/pg/StartPay/' . $responseData['data']['authority'];
            }

            throw new CreateLinkPaymentException(/*extra_data:[$responseData]*/);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

}
