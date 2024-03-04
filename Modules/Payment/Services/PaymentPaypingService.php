<?php

namespace Modules\Payment\Services;

use App\Exceptions\Contracts\CreateLinkPaymentException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Exception;

class PaymentPaypingService
{
    private string $TokenCode;
    private static string $PAYMENT_URL_V1 = 'https://api.payping.ir/v1/';
    private static string $PAYMENT_URL_V2 = 'https://api.payping.ir/v2/';
    private static string $PAYMENT_ENDPOINT = 'pay';
    private static string $VERIFY_ENDPOINT = 'pay/verify';

    public function __construct(string|null $token = null)
    {
        $this->TokenCode = $token ?? env('PAYMENT_PAYPING_TOKEN');
    }


    private function getErrorMessage($errorCode): string
    {
        return self::ERROR_CODES[$errorCode] ?? 'Unknown error occurred';
    }

    private const ERROR_CODES = [
        1 => 'تراكنش توسط شما لغو شد',
        2 => 'رمز کارت اشتباه است.',
        3 => 'cvv2 یا تاریخ انقضای کارت وارد نشده است',
        4 => 'موجودی کارت کافی نیست.',
        5 => 'تاریخ انقضای کارت گذشته است و یا اشتباه وارد شده.',
        6 => 'کارت شما مسدود شده است',
        7 => 'تراکنش مورد نظر توسط درگاه یافت نشد',
        8 => 'بانک صادر کننده کارت شما مجوز انجام تراکنش را صادر نکرده است',
        9 => 'مبلغ تراکنش مشکل دارد',
        10 => 'شماره کارت اشتباه است.',
        11 => 'ارتباط با درگاه برقرار نشد، مجددا تلاش کنید',
        12 => 'خطای داخلی بانک رخ داده است',
        15 => 'این تراکنش قبلا تایید شده است',
        18 => 'کاربر پذیرنده تایید نشده است',
        19 => 'هویت پذیرنده کامل نشده است و نمی تواند در مجموع بیشتر از ۵۰ هزار تومان دریافتی داشته باشد',
        25 => 'سرویس موقتا از دسترس خارج است، لطفا بعدا مجددا تلاش نمایید',
        26 => 'کد پرداخت پیدا نشد',
        27 => 'پذیرنده مجاز به تراکنش با این مبلغ نمی باشد',
        28 => 'لطفا از قطع بودن فیلتر شکن خود مطمئن شوید',
        29 => 'ارتباط با درگاه برقرار نشد',
        31 => 'امکان تایید پرداخت قبل از ورود به درگاه بانک وجود ندارد',
        38 => 'آدرس سایت پذیرنده نا معتبر است',
        39 => 'پرداخت ناموفق، مبلغ به حساب پرداخت کننده برگشت داده خواهد شد',
        44 => 'RefId نامعتبر است',
        46 => 'توکن ساخت پرداخت با توکن تایید پرداخت مغایرت دارد',
        47 => 'مبلغ تراکنش مغایرت دارد',
        48 => 'پرداخت از سمت شاپرک تایید نهایی نشده است',
        49 => 'ترمینال فعال یافت نشد، لطفا مجددا تلاش کنید'
    ];

    public function createLinkPayment($clientRefId, $mobile, $amount, $description, $returnUrl, $payerName): string
    {
        try {
            $data = [
                'clientRefId' => $clientRefId, /* شماره فاکتور */
                'payerIdentity' => $mobile, /* شماره موبایل یا ایمیل پرداخت کننده */
                'payerName' => $payerName, /* نام کاربر پرداخت کننده */
//                'amount' => is_string($amount) ? floatval($amount) : $amount, /* required *//* مبلغ تراکنش */
                'amount' => 2000, /* required *//* مبلغ تراکنش */
                'Description' => $description, /* توضیحات */
                'returnUrl' => $returnUrl, /* required *//* آدرس برگشتی از سمت درگاه */
            ];

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->TokenCode,
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'curl' => [CURLOPT_SSL_VERIFYPEER => false],
            ])->post(self::$PAYMENT_URL_V1 . self::$PAYMENT_ENDPOINT, $data);

            $statusCode = $response->status();
            $responseData = $response->json();
            $code = $responseData['code'] ?? null;
            Log::info('',$responseData);

            if ($statusCode === Response::HTTP_OK && $responseData['code']) {
                return self::$PAYMENT_URL_V1 . 'pay/gotoipg/' . $code;
            }

            throw new CreateLinkPaymentException(/*extra_data:[$response->json()]*/);

        } catch (Exception $exception) {
            throw new CreateLinkPaymentException($this->getErrorMessage($exception->getCode()));
        }
    }

    public function confirmPayment($authority, $amount, $factor_id): bool
    {
        try {
            $data = [
                'amount' => $amount,
                'refId' => $authority,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorizations' => "Bearer $this->TokenCode"
            ])->post(self::$PAYMENT_URL_V2 . self::$VERIFY_ENDPOINT, $data);

            $statusCode = $response->status();
            $responseData = $response->json();

            if ($statusCode === 200 && isset($responseData['data']['code']) && $responseData['data']['code'] === 100) {
                if ($factor_id == $responseData['data']['ref_id'] && $amount == $responseData['data']['amount']) {
                    return true;
                } else {
                    throw new Exception('مشکل مطابقت فاکتور یا مبلغ در تایید پرداخت');
                }
            }
            throw new Exception('Payment verification failed');

        } catch (Exception $exception) {
            // Handle exceptions
            throw new CreateLinkPaymentException($this->getErrorMessage($exception->getCode()));
        }
    }

    /**
     * Create a link for a multi-payment transaction.
     * ساخت لینک برای تراکنش چندگانه پرداخت
     * @param string $payerName The name of the payer initiating the payment. نام پرداخت کننده که تراکنش را آغاز کرده است.
     * @param array $pairs An array of payment pairs, each containing 'payerIdentity', 'amount', and 'description'.
     *                      آرایه ای از جفت های پرداخت، هر کدام شامل 'payerIdentity'، 'amount' و 'description' هستند.
     * @param string $returnUrl The URL to which the user should be redirected after completing the payment process.
     *                          آدرس URL که کاربر باید پس از تکمیل فرایند پرداخت به آن هدایت شود.
     * @param string $clientRefId The client reference ID, representing a unique identifier for the transaction.
     *                            شناسه مرجع مشتری، که یک شناسه منحصر به فرد برای تراکنش را نشان می دهد.
     * @return string The generated payment link. لینک پرداخت تولید شده.
     * @throws CreateLinkPaymentException
     */
    public function create_multi_payment(string $payerName, array $pairs, string $returnUrl, string $clientRefId): string
    {
        try {

            $data = [
                'payerName' => $payerName,
                'pairs' => $pairs,
                'returnUrl' => $returnUrl,
                'clientRefId' => $clientRefId,
            ];

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->TokenCode,
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'curl' => [CURLOPT_SSL_VERIFYPEER => false]
            ])->post(self::$PAYMENT_URL_V2 . self::$PAYMENT_ENDPOINT, $data);

            $statusCode = $response->status();
            $responseData = $response->json();

            $code = $responseData['code'] ?? null;

            if ($statusCode === Response::HTTP_OK && $responseData['code']) {
                return self::$PAYMENT_URL_V1 . 'pay/gotoipg/' . $code;
            }

            throw new CreateLinkPaymentException(/*extra_data:[$response->json()]*/);

        } catch (Exception $exception) {
            // Handle exceptions
            throw new CreateLinkPaymentException($this->getErrorMessage($exception->getCode()));
        }
    }

}
