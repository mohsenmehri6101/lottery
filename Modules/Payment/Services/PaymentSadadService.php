<?php

namespace Modules\Payment\Services;

use App\Exceptions\Contracts\CreateLinkPaymentException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Exception;

class PaymentSadadService
{
    private string $TokenCode;
    private static string $PAYMENT_BASE_URL = 'https://sadad.shaparak.ir';
    private static string $PAYMENT_CREATE_LINK_ENDPOINT = '/api/v0/Request/PaymentRequest';
    private static string $VERIFY_ENDPOINT = 'pay/verify';

    public function __construct(string|null $token = null)
    {
        $this->TokenCode = $token ?? env('PAYMENT_SADAD_TOKEN');
    }

    public function createLinkPayment($MerchantId = null, $TerminalId = null, $Amount = null, $OrderId = null, $LocalDateTime = null, $ReturnUrl = null, $SignData = null, $AdditionalData = null, $MultiplexingData = null, $UserId = null, $ApplicationName = null, $PanAuthenticationType = null, $NationalCode = null, $CardHolderIdentity = null, $SourcePanList = [], $NationalCodeEnc = null, $SourcePanList = null)
    {
        try {
            # MerchantId               string   required
            # TerminalId               string   required
            # Amount                   string   required
            # OrderId                  number   required
            # LocalDateTime            DateTime required
            # ReturnUrl                string   required
            # SignData                 string   required
            # AdditionalData           string   required
            # MultiplexingData         json     required
            # UserId                   number   required
            # ApplicationName          string   required
            # PanAuthenticationType    number   required
            # NationalCode             string   required
            # CardHolderIdentity       string   required
            # SourcePanList            list     required
            # NationalCodeEnc          string   required


            $data = [
                'MerchantId' => $MerchantId,
                'TerminalId' => $TerminalId,
                'Amount' => $Amount,
                'OrderId' => $OrderId,
                'LocalDateTime' => $LocalDateTime,
                'ReturnUrl' => $ReturnUrl,
                'SignData' => $SignData,
                'AdditionalData' => $AdditionalData,
                'MultiplexingData' => $MultiplexingData,
                'UserId' => $UserId,
                'ApplicationName' => $ApplicationName,
                'PanAuthenticationType' => $PanAuthenticationType,
                'NationalCode' => $NationalCode,
                'CardHolderIdentity' => $CardHolderIdentity,
                'SourcePanList' => $SourcePanList,
                'NationalCodeEnc' => $NationalCodeEnc,
            ];

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->TokenCode,
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'curl' => [CURLOPT_SSL_VERIFYPEER => false],
            ])->post(self::$PAYMENT_BASE_URL . self::$PAYMENT_CREATE_LINK_ENDPOINT, $data);

            $statusCode = $response->status();
            // output is : ResCode ,Token ,Description
            $responseData = $response->json();
            $ResCode = $responseData['ResCode'] ?? null;
            $Token = $responseData['Token'] ?? null;
            $Description = $responseData['Description'] ?? null;
            $Description = $responseData['Description'] ?? null;

            $code =null;
            if ($statusCode === Response::HTTP_OK && $responseData['code']) {
                return self::$PAYMENT_BASE_URL . 'pay/gotoipg/' . $code;
            }

            throw new CreateLinkPaymentException(/*extra_data:[$response->json()]*/);

        } catch (Exception $exception) {
            dd_json($exception);
            throw new $exception;
        }
    }

    public function confirmPayment($refid, $amount): bool
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
            ])->post(self::$PAYMENT_BASE_URL . self::$VERIFY_ENDPOINT, $data);

            $statusCode = $response->status();
            return $statusCode === Response::HTTP_OK;

        } catch (Exception $exception) {
            throw new $exception;
        }
    }

}
