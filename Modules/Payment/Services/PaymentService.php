<?php

namespace Modules\Payment\Services;

use App\Exceptions\Contracts\ForbiddenCustomException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Authentication\Entities\User;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Entities\Reserve;
use Modules\Payment\Entities\Factor;
use Modules\Payment\Entities\Transaction;
use Modules\Payment\Http\Repositories\FactorRepository;
use Modules\Payment\Http\Repositories\PaymentRepository;
use Modules\Payment\Http\Requests\Payment\MyPaymentsRequest;
use Modules\Payment\Http\Requests\Payment\PaymentCreateLinkRequest;
use Modules\Payment\Http\Requests\Payment\PaymentIndexRequest;
use Modules\Payment\Http\Requests\Payment\PaymentShowRequest;
use Modules\Payment\Entities\Payment;
use Illuminate\Support\Facades\Validator;

class PaymentService
{
    const USER_ID_SYSTEM = 1;

    public function __construct(public PaymentRepository $paymentRepository)
    {
    }

    public function index(PaymentIndexRequest $request)
    {
        try {
            $fields = $request->validated();
            return $this->paymentRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function myPayments(MyPaymentsRequest $request)
    {
        try {

            if (!is_gym_manager()) {
                throw new ForbiddenCustomException();
            }

            if (is_array($request)) {
                $myPaymentsRequest = new MyPaymentsRequest();
                $fields = Validator::make(data: $request,
                    rules: $myPaymentsRequest->rules(),
                    attributes: $myPaymentsRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            $user_id = get_user_id_login();
            $fields['user_id'] = $user_id;

            $query = $this->paymentRepository->queryFull(inputs: $fields);

            return $this->paymentRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(PaymentShowRequest $request, $payment_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $withs = $withs ?? [];
            return $this->paymentRepository->withRelations(relations: $withs)->findOrFail($payment_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private static function convertToToman($amountInRial)
    {
        return $amountInRial / 10; // Example conversion: rial to toman
    }

    public function createLinkPayment(PaymentCreateLinkRequest|array $request): ?string
    {
        DB::beginTransaction();
        try {

            if (is_array($request)) {
                $loginRequest = new PaymentCreateLinkRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules(),
                    attributes: $loginRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $factor_id
             */
            extract($fields);

            /** @var FactorRepository $factorRepository */
            $factorRepository = resolve('FactorRepository');

            /** @var Factor $factor */
            $factor = $factorRepository->findOrFail($factor_id);
            /* --------------------------------------------------------- */
            /** @var PaymentPaypingService $PaymentPaypingService */
            $PaymentPaypingService = resolve('PaymentPaypingService');

            $clientRefId = $factor->id ?? null;
            $mobile = $factor?->user?->mobile ?? null;
            $amount = $factor->total_price ?? null;
            /** @var User $user */
            $user = $factor->user;
            $returnUrl = route('api_v1_payment_payments_confirm_payment_get') ?? null;
            # $returnUrl = route('web.confirm_payment_get') ?? null;
            $payerName = $factor?->user?->full_name ?? null;
            # todo better set description of factor and total-price and dated-at and reserve template id and gym_id
            $description = isset($description) && filled($description) ? $description : $mobile;
            # Updated description to include more relevant information

            /** @var Payment $payment */ // todo should be active.
            $payment = $factor->payments()->create([
                'status' => Payment::status_unpaid,
                'resnumber' => Payment::resnumberUnique(),
                'amount' => $amount,
                'user_id' => $user->id,
            ]);


            $url = $PaymentPaypingService->createLinkPayment(
              clientRefId: $payment->resnumber,
              mobile: $mobile,
              amount: $amount,
              returnUrl: $returnUrl,
              description: $description,
              payerName: $payerName,
            );


            # todo this is fake.
//            $url = Str::random();
//            self::confirmPaymentTest($payment);

            DB::commit();
            return $url;
        } catch (Exception $exception) {
            DB::rollBack();
            # Log::info('', [$exception->getMessage(), $exception->getLine(), $exception->getTrace()]);
            throw new $exception;
        }
    }

    public function confirmPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->all();
            $ref_id = $fields['refid'] ?? null;
            $resnumber = $client_ref_id = $fields['clientrefid'] ?? null;
            ####################################################################
            /** @var PaymentPaypingService $PaymentPaypingService */
            $PaymentPaypingService = resolve('PaymentPaypingService');
            ####################################################################
            /** @var Payment $payment */
            $payment = Payment::query()->where('resnumber', $resnumber)->firstOrFail();
            ####################################################################
            /** @var Factor $factor */
            $factor = $payment->factor;
            ####################################################################
            $factor_id = $factor->payments()->latest()->first()->id;
            if ($PaymentPaypingService->confirmPayment(authority: $ref_id, amount: $factor->total_price, factor_id: $factor_id)) {
                $factor->status = Factor::status_paid;
                $payment->status = Payment::status_paid;
                $factor->reserves()->update(['status' => Reserve::status_reserved]);
                $payment->save();
                $factor->payment_id_paid = $payment->id;
                $factor->save();
                self::save_transactions($factor);
            }
            DB::commit();

            return $payment->resnumber;

        } catch (Exception $exception) {
            DB::rollBack();
            # Log::info('', [$exception->getMessage(), $exception->getLine(), $exception->getTrace()]);
            throw new $exception;
        }
    }

    public static function confirmPaymentTest(Payment $payment): bool
    {
        DB::beginTransaction();
        try {

            /** @var Factor $factor */
            $factor = $payment->factor;

            $factor->payments()->latest()->first()->id;

            if (true) {
                $factor->status = Factor::status_paid;
                $payment->status = Payment::status_paid;
                $factor->reserves()->update(['status' => Reserve::status_reserved]);
                $payment->save();
                $factor->payment_id_paid = $payment->id;
                $factor->save();
                self::save_transactions($factor);
            }

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new $exception;
        }
    }

    public static function save_transactions(Factor $factor): void
    {
        /** @var Gym $gym */
        $gym = $factor->gym;

        // محاسبه مقدار سود مسئول سالن
        $profit_share_percentage = $gym->profit_share_percentage;
        $user_gym_manager_id = $gym->user_gym_manager_id;
        # #### #### #### #### #### #### #### #### #### #### #### #### #### #### #### #### ####

        // محاسبه مقدار درآمد مسئول سایت
        $site_income = $factor->total_price * ($profit_share_percentage / 100);

        // سود مسئول سالن
        $gym_profit = $factor->total_price - $site_income;

        $user_resource = $factor->user_id;
        $user_resource = filled($user_resource) ? $user_resource : get_user_id_login();

        // ثبت رکورد در جدول تراکنش‌ها برای مسئول سالن
        Transaction::query()->create([
            'user_destination' => $user_gym_manager_id,
            // todo should be can set user_id if gym-manager want set reserve from user. or not ?!!
            'user_resource' => $user_resource,
            'price' => $gym_profit,
            'description' => 'تراکنش برای مسئول سالن',
            'specification' => Transaction::SPECIFICATION_CREDIT, // بستانکار
            'transaction_type' => Transaction::TRANSACTION_TYPE_DEPOSIT, // واریز
            'operation_type' => Transaction::OPERATION_TYPE_PAYMENT_TO_GYM_MANAGER, // پرداخت به مدیر سالن
        ]);

        // ثبت رکورد در جدول تراکنش‌ها برای مسئول سایت
        Transaction::query()->create([
            'user_destination' => self::USER_ID_SYSTEM,
            // todo should be can set user_id if gym-manager want set reserve from user. or not ?!!
            'user_resource' => $user_resource,
            'price' => $site_income,
            'description' => 'تراکنش برای مسئول سایت',
            'specification' => Transaction::SPECIFICATION_DEBIT, // بدهکار
            'transaction_type' => Transaction::TRANSACTION_TYPE_DEPOSIT, // واریز
            'operation_type' => Transaction::OPERATION_TYPE_RETURN_TO_USER, // بازگشت مبلغ به کاربر
        ]);

    }

    public function destroy($payment_id): bool
    {
        DB::beginTransaction();
        try {
            # find payment
            /** @var Payment $payment */
            $payment = $this->paymentRepository->findOrFail($payment_id);
            # ###########
            # delete payment
            $this->paymentRepository->delete($payment);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function createLinkPaymentSadad(PaymentCreateLinkRequest $request): ?string
    {
        try {
            $fields = $request->validated();

            /**
             * @var $factor_id
             */
            extract($fields);

            /** @var FactorRepository $factorRepository */
            $factorRepository = resolve('FactorRepository');

            /** @var Factor $factor */
            $factor = $factorRepository->findOrFail($factor_id);
            /* --------------------------------------------------------- */

            /** @var PaymentPaypingService $PaymentPaypingService */
            $PaymentPaypingService = resolve('PaymentPaypingService');

            $clientRefId = $factor->id ?? null;
            $mobile = $factor?->user?->mobile ?? null;
            $amount = $factor->total_price ?? null;
            /** @var User $user */
            $user = $factor->user;
            $returnUrl = route('api_v1_payment_payments_confirm_payment_get') ?? null;
            $payerName = $factor?->user?->full_name ?? null;
            $description = isset($description) && filled($description) ? $description : $mobile;

            /** @var $payment $payment */
            $payment = $factor->payments()->create([
                'status' => Payment::status_unpaid,
                'resnumber' => Payment::resnumberUnique(),
                'amount' => $amount,
                'user_id' => $user->id,
            ]);
            $url = $PaymentPaypingService->createLinkPayment(
                clientRefId: $payment->resnumber,
                mobile: $mobile,
                amount: $amount,
                returnUrl: $returnUrl,
                description: $description,
                payerName: $payerName,
            );

            return '';
        } catch (Exception $exception) {
            throw new $exception;
        }
    }

    public static function fake_payment(Factor $factor): void
    {
        Payment::query()->create([
            'status' => Payment::status_paid,
            'resnumber' => Str::random(5),
            'amount' => $factor->total_price,
            'factor_id' => $factor->id,
            'user_id' => $factor->user_id,
        ]);

        $factor->update(['status' => Factor::status_paid]);
    }
}
